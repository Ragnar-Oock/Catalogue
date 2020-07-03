<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Document;
use App\Entity\Edition;
use App\Entity\Editor;
use App\Entity\ParticipationType;
use App\Entity\Type;
use App\Entity\Writer;
use App\Repository\AuthorRepository;
use App\Repository\DocumentRepository;
use App\Repository\EditorRepository;
use App\Repository\ParticipationTypeRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LoadRevueCommand extends Command
{
    protected static $defaultName = 'app:load:revue';

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ParameterBagInterface $params,
        DocumentRepository $dr,
        EditorRepository $er,
        AuthorRepository $ar,
        ParticipationTypeRepository $ptr,
        TypeRepository $tr
    ) {
        parent::__construct();
        $this->em = $em;
        $this->params = $params;
        $this->serializer = $serializer;
        $this->dr = $dr;
        $this->er = $er;
        $this->ar = $ar;
        $this->ptr = $ptr;
        $this->tr = $tr;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $file = $this->params->get('kernel.project_dir') . '/data/catalogueFondsRevue.csv';
        $datas = $this->serializer->decode(file_get_contents($file), 'csv');
        $datas = array_slice($datas, 473);

        // pour chaque ligne:
        foreach ($datas as $line) {
            if (empty($line['fonds'])) {
                break;
            }
            $miscData = array();

            // 	creer une nouvelle entité
            $edition = new Edition();
            if (empty($line['ISSN'])) {
                $edition->setIssn($line['ISSN']);
            }
            if (empty($line['ISBN'])) {
                $edition->setIsbn($line['ISBN']);
            }
            if (empty($line['Tome'])) {
                $edition->setTome($line['Tome']);
            }
            if (empty($line['Nb_Pages'])) {
                $edition->setPages($line['Nb_Pages']);
            }
            if (empty($line['Notes'])) {
                $edition->setNotes($line['Notes']);
            }

            // inventory numbers are assumed to be consistent and thus not validated
            $edition->setInventoryNumber($line['Numero_inventaire']);

            if (!empty($line['Annee_edition']) && $line['Annee_edition'] !== 's.d.') {
                try {
                    // handle values like "something (year)"
                    $year = explode('(', $line['Annee_edition']);
                    $year = count($year) > 1 ? $year[1]: $year[0];
                    $year = explode(')', $year);
                    // handle values like "year-year" keep the first year
                    $year = explode('-', $year[0]);
                    // handle values like "year something"
                    $year = explode(' ', $year[0]);
                    $edition->setPublishedAt(\DateTime::createFromFormat('Y/m/d', $year[0] . '/01/01'));
                } catch (\Throwable $th) {
                    $io->error($line['Annee_edition'] . ' => '. $year[0] . '/01/01');
                    throw $th;
                }
            }

            // find or create document
            $document = new Document();
            $document->setTitle($line['Titre']);
            $document->setSubtitle($line['Sous_titre']);

            $documentInstance = $this->dr->findInstance($document);
            if ($documentInstance !== null) {
                $document = $documentInstance;
            } else {
                $this->em->persist($document);
            }

            $edition->setDocument($document);



            $type = new Type();
            $type->setTitle($line['Type_document']);

            $typeInstance = $this->tr->findInstance($type);
            if ($typeInstance !== null) {
                $type = $typeInstance;
            } else {
                $this->em->persist($type);
            }
            $edition->setType($type);

            if (!empty($line['Numero_edition'])) {
                $miscData["Numéro d'édition"] = $line['Numero_edition'];
            }
            if (!empty($line['Numero_de_volume'])) {
                $miscData["Numéro de volume"] = $line['Numero_de_volume'];
            }
            if (!empty($line['Nb_volumes'])) {
                $miscData["Nombre de volumes"] = $line['Nb_volumes'];
            }
            if (!empty($line['Format'])) {
                $miscData["Format"] = $line['Format'];
            }
            if (!empty($line['Privileges_royaux_et_date_approbation'])) {
                $miscData["Privilèges royaux et date d'approbation"] = $line['Privileges_royaux_et_date_approbation'];
            }
            if (!empty($line['Ex_libris'])) {
                $miscData["Ex libris"] = $line['Ex_libris'];
            }
            if (!empty($line['Lien_hypertexte'])) {
                $miscData["Lien hypertexte"] = $line['Lien_hypertexte'];
            }
            if (!empty($line['Illustrations'])) {
                $miscData["Illustrations"] = $line['Illustrations'];
            }
            if (!empty($line['Provenance'])) {
                $miscData["Provenance"] = $line['Provenance'];
            }
            if (!empty($line['Financement'])) {
                $miscData["Financement"] = $line['Financement'];
            }
            if (!empty($line['Classement_thematique'])) {
                $miscData["Classement thematique"] = $line['Classement_thematique'];
            }
            if (!empty($line['Lieu_edition'])) {
                $miscData["lieu d'édition"] = $line['Lieu_edition'];
            }

            $edition->setMiscData($miscData);

            // 	si l'éditeur existe deja:
            // 		ajouter l'editeur a l'instance
            // 	sinon:
            // 		créer puis lier l'éditeur
            $editor = new Editor();
            $editor->setName($line['Editeur']);

            $editorInstance = $this->er->findInstance($editor);
            if ($editorInstance !== null) {
                $editor = $editorInstance;
            } else {
                $this->em->persist($editor);
            }
            $edition->setEditor($editor);

            $this->em->persist($edition);

            // 	pour chaque auteur:
            // 		si l'auteur existe deja:
            // 			lier l'auteur a l'édition
            // 			si le type de participation existe deja:
            // 				lier le type de participation
            // 			sinon:
            // 				ajouter puis lier
            for ($i = 1; $i <= 6; $i++) {
                if (!empty($line['Auteur' . $i])) {
                    $author = new Author();

                    $author->setName($line['Auteur' . $i]);

                    $authorInstance = $this->ar->findInstance($author);

                    if ($authorInstance != null) {
                        $author = $authorInstance;
                    } else {
                        $this->em->persist($author);
                    }

                    $participationType = new ParticipationType();
                    $participationType->setTitle($line['Fonction' . $i]);

                    $participationTypeInstance = $this->ptr->findInstance($participationType);
                    if ($participationTypeInstance !== null) {
                        $participationType = $participationTypeInstance;
                    } else {
                        $this->em->persist($participationType);
                    }

                    $writer = new Writer();

                    $writer->setAuthor($author);
                    $writer->setEdition($edition);
                    $writer->setParticipationType($participationType);

                    $this->em->persist($writer);
                }
            }

            $this->em->persist($edition);

            $this->em->flush();
        }

        $this->em->flush();

        $io->success('everithing loaded in the database :)');

        return 0;
    }
}
