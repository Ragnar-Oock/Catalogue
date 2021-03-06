<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Document;
use App\Entity\Edition;
use App\Entity\Editor;
use App\Entity\Fond;
use App\Entity\ParticipationType;
use App\Entity\Type;
use App\Entity\Writer;
use App\Repository\AuthorRepository;
use App\Repository\DocumentRepository;
use App\Repository\EditorRepository;
use App\Repository\FondRepository;
use App\Repository\ParticipationTypeRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LoadAncienCommand extends Command
{
    protected static $defaultName = 'app:load:ancien';

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ParameterBagInterface $params,
        DocumentRepository $dr,
        EditorRepository $er,
        AuthorRepository $ar,
        ParticipationTypeRepository $ptr,
        TypeRepository $tr,
        FondRepository $fr
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
        $this->fr = $fr;
    }

    protected function configure()
    {
        $this
            ->setDescription('Charge les données legacy dans la base');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        $file = $this->params->get('kernel.project_dir') . '/data/catalogueFondsAncien.csv';
        $datas = $this->serializer->decode(file_get_contents($file), 'csv');

        $progressBar = new ProgressBar($output, count($datas));

        $progressBar->start();

        // pour chaque ligne:
        foreach ($datas as $line) {
            if ($line['fonds'] == "") {
                break;
            }
            $miscData = array();

            // 	creer une nouvelle entité
            $edition = new Edition();

            // pour chaque editeur:
            if ($line['Editeur1'] !== "") {
                // 	si l'éditeur existe deja:
                // 		ajouter l'editeur a l'instance
                // 	sinon:
                // 		créer puis lier l'éditeur
                $editor = new Editor();
                $editor->setName($line['Editeur1']);
                $editor->setAddress($line['Adresse_editeur1']);

                $editorInstance = $this->er->findInstance($editor);
                if ($editorInstance !== null) {
                    $editor = $editorInstance;
                } else {
                    $this->em->persist($editor);
                }
                $edition->setEditor($editor);

                // $this->em->persist($edition);
            }
            if ($line['Editeur2'] !== "") {
                $editor2 = new Editor();
                $editor2->setName($line['Editeur2']);
                $editor2->setAddress($line['Adresse_editeur2']);

                $editor2Instance = $this->er->findInstance($editor);
                if ($editor2Instance !== null) {
                    $editor2 = $editor2Instance;
                } else {
                    $this->em->persist($editor2);
                }

                // create a second edition the assign the editor to
                $edition2 = new Edition();
                $edition2->setEditor($editor2);

                // $this->em->persist($edition2);
            }


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
            $edition->setInventoryNumber($line['Numero_inventaire']);

            if (!empty($line['Annee_edition']) && $line['Annee_edition'] !== 's.d.') {
                $year = explode('-', $line['Annee_edition']);
                $edition->setPublishedAt(\DateTime::createFromFormat('Y/m/d', $year[0] . '/01/01'));
            }


            if (isset($edition2)) {
                if (empty($line['ISSN'])) {
                    $edition2->setIssn($line['ISSN']);
                }
                if (empty($line['ISBN'])) {
                    $edition2->setIsbn($line['ISBN']);
                }
                if (empty($line['Tome'])) {
                    $edition2->setTome($line['Tome']);
                }
                if (empty($line['Nb_Pages'])) {
                    $edition2->setPages($line['Nb_Pages']);
                }
                if (empty($line['Notes'])) {
                    $edition2->setNotes($line['Notes']);
                }
                $edition2->setInventoryNumber($edition->getInventoryNumber() + 10000);

                if (!empty($line['Annee_edition']) && $line['Annee_edition'] !== 's.d.') {
                    $year = explode('-', $line['Annee_edition']);
                    $edition2->setPublishedAt(\DateTime::createFromFormat('Y/m/d', $year[0] . '/01/01'));
                }
            }


            // find or create document
            $document = new Document();
            $document->setTitle($line['Titre']);
            $document->setAlttitle($line['Titre_moderne']);
            $document->setSubtitle($line['Sous_titre']);

            $documentInstance = $this->dr->findInstance($document);
            if ($documentInstance !== null) {
                $document = $documentInstance;
            } else {
                $this->em->persist($document);
            }

            $edition->setDocument($document);
            if (isset($edition2)) {
                $edition2->setDocument($document);
            }

            $fond = new Fond();
            $fond->setTitle($line['fonds']);
            $fond->setDescription('');

            $fondInstance = $this->fr->findInstance($fond);
            if ($fondInstance !== null) {
                $fond = $fondInstance;
            } else {
                $this->em->persist($fond);
            }

            $edition->setFond($fond);
            if (isset($edition2)) {
                $edition2->setFond($fond);
            }

            $type = new Type();
            $type->setTitle($line['Type_document']);

            $typeInstance = $this->tr->findInstance($type);
            if ($typeInstance !== null) {
                $type = $typeInstance;
            } else {
                $this->em->persist($type);
            }

            $edition->setType($type);
            if (isset($edition2)) {
                $edition2->setType($type);
            }


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

            $edition->setMiscData($miscData);
            if (isset($edition2)) {
                $edition2->setMiscData($miscData);
            }

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

                    // if edition 2 exist appli and persist the writter
                    if (isset($edition2)) {
                        $writer2 = new Writer();
                        $writer2->setAuthor($author);
                        $writer2->setEdition($edition2);
                        $writer2->setParticipationType($participationType);

                        $this->em->persist($writer2);
                    }
                    $this->em->persist($writer);
                }
            }

            $this->em->persist($edition);
            if (isset($edition2)) {
                $this->em->persist($edition2);
            }

            $this->em->flush();
            unset($edition);
            if (isset($edition2)) {
                unset($edition2);
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $io->success('everithing loaded in the database :)');

        return 0;
    }
}
