<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductAdminController extends Controller
{
    /**
     * @Route("/admin/products", name="product_list")
     */
    public function listAction()
    {
        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products/new", name="product_new")
     */
    public function newAction(Request $request)
    {
        return $this->render('product/new.html.twig');
    }

    /**
     * Saves a product to the database
     *
     * @Route("/admin/products/save", name="product_save", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $product = new Product();
        $product->setName($request->get('name'));
        $product->setPrice($request->get('price'));
        $product->setDescription($request->get('description'));
        $product->setAuthor($this->getUser());

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        $this->addFlash('success', 'Product created FTW!');


        return $this->redirectToRoute('product_list');
    }

    /**
     * Deletes a product from the database
     *
     * @Route("/admin/products/delete/{id}", name="product_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'The product was deleted');

        return $this->redirectToRoute("product_list");
    }
}
