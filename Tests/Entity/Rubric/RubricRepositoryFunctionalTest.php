<?php
namespace Iphp\CoreBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Application\Iphp\CoreBundle\Entity\Rubric;

class RubricRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()
                ->get('doctrine.orm.entity_manager');
    }

    public function testFillTree()
    {
        $repo = $this->_em->getRepository('ApplicationIphpCoreBundle:Rubric');

        // $this->assertEquals(count($results), 1);


        $connection = $this->_em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('rubric__rubric', true /* whether to cascade */));

        // print get_class ($root);

        //Создаем корневую рубрику
        $rootRubric = new Rubric();
        $rootRubric->setTitle('Рубрики')->setPath('')->setStatus(true);
        $this->_em->persist($rootRubric);
        $this->_em->flush();


        //Создаем рубрики 11111,22222,33333
        $rubrics = array();
        for ($i = 1; $i <= 3; $i++)
        {
            $rubrics[$i] = new Rubric();
            $rubrics[$i]->setTitle(str_repeat($i, 5))->setPath(str_repeat($i, 5))->setStatus(true)->setParent($rootRubric);

            $this->_em->persist($rubrics[$i]);
        }

        print "\n" . 'childs first save';
        $this->_em->flush();
        print "\nok;";

        $repo->clear();
        unset($rubrics);



        //У рубрики 33333 left=6 , right = 7
        $rubric3 = $repo->find(4);
        $this->assertEquals($rubric3->getLeft(), 6);
        $this->assertEquals($rubric3->getRight(), 7);


        //У рубрики 11111 left=2, right = 3
        $rubric1 = $repo->find(2);
        $this->assertEquals($rubric1->getLeft(), 2);
        $this->assertEquals($rubric1->getRight(), 3);


        //Ставим рубрику 1 после рубрики 3 в той же ветке (уровни не должны меняться)
        $repo->persistAsNextSiblingOf($rubric1, $rubric3);
        $this->_em->flush();
        $repo->clear();


        /*    $repo->persistAsFirstChild( $rubric3 );

       print $rubric3.',left:'.$rubric3->getLeft();*/

        print "\n";
        print_r($repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* load all children, not only direct */
            array('decorate' => true)));


        //У рубрики 11111 Left должен быть равен 6, Right должен быть равен 7 (  1 23 45 _6_
        $rubric1 = $repo->find(2);
        $this->assertEquals($rubric1->getLeft(), 6);
        $this->assertEquals($rubric1->getRight(), 7);
    }
}
