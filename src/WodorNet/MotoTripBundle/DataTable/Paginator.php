<?php
namespace WodorNet\MotoTripBundle\DataTable;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator as KnpPaginator;
use Symfony\Bundle\TwigBundle\TwigEngine;


class Paginator {
    
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    protected $paginator;
    
    
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    
    /**
     * @var string
     * template for columns separated by <!--COLUMNSEPARATOR-->
     */
    protected $templateName;
    
    public function __construct(KnpPaginator $paginator, Request $request, TwigEngine $templating) {
        $this->paginator = $paginator;
        $this->request = $request;
        $this->templating = $templating;
    }
    
    
    public function setItemTemplate($template) {
        $this->templateName = $template;
    }

    public function paginate($data) {
        
        $offset = $this->request->get('iDisplayStart', 0);
        $limit = $this->request->get('iDisplayLength', 10);
        
        $pagination = $this->paginator->paginate($data , self::offsetToPage($offset, $limit), $limit);
        
        $output = array(
                "sEcho" => (int)$this->request->get('sEcho',0),
                "iTotalRecords" => $pagination->getTotalItemCount(),
                "iTotalDisplayRecords" => $pagination->getTotalItemCount(),
                "aaData" => $this->formatRows($pagination)
        );
        
        return $output;
    }
    
    private function formatRows($collection) {
        foreach($collection as $item) {
            $ret[] = explode('<!--COLUMNSEPARATOR-->',$this->templating->render($this->templateName, array('item' => $item)));
        }
        return $ret;
    }
    
    public function offsetToPage($offset, $limit) {
        return ceil($offset/$limit)+1;
    }
}


