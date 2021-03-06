<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/blog", name="blog_")
*/
class BlogController extends AbstractController
{
 /**
 * Show all row from article's entity
 *
 * @Route("/", name="index")
 * 
 */
 public function index(): Response
 {
      $articles = $this->getDoctrine()
          ->getRepository(Article::class)
          ->findAll();
      if (!$articles) {
          throw $this->createNotFoundException(
          'No article found in article\'s table.'
          );
      }
      return $this->render(
              'blog/index.html.twig',
              ['articles' => $articles]
      );
}
    /**
     * @Route("/show/{slug}",
     *          methods={"GET"},
     *          requirements={"slug"="[a-z0-9\-]+"},
     *          defaults={"slug"="article-sans-titre"},
     *          name="show")
     */
    public function show(string $slug)
    {
        $cleanSlug = ucwords(trim(str_replace('-',' ',$slug)));
    
        return $this->render('/blog/show.html.twig',['cleanSlug'=>$cleanSlug]);
    }
   /**
     * @Route("/category/{categoryName}",
     *          methods={"GET"},
     *          name="show_category")
     */
   public function showByCategory(string $categoryName) : Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category\'s name has been sent to find articles in article\'s table.');
        }
        
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);
            
        $limit=3;    
        $articles = $category->getArticle();
        return $this->render('/blog/category.html.twig', ['articles' => $articles,'category' => $category,]
        
        );
    }   
}