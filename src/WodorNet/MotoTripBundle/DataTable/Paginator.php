<?php
namespace WodorNet\MotoTripBundle\DataTable;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator as KnpPaginator;


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
     * @var callable
     * method which converts array of objects  into dataTable friendly array of arrays with cols content
     */
    protected $rowFormatter;
    
    public function __construct(KnpPaginator $paginator, Request $request) {
        $this->paginator = $paginator;
        $this->request = $request;
        $this->rowFormatter = 'iterator_to_array';
    }
    
    
    public function setRowFormatter($callable) {
        $this->rowFormatter = $callable;
    }
    public function getRowFormatter() {
        return $this->rowFormatter;
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
            $m = $this->rowFormatter; 
            $ret[] = $m($item);
        }
        return $ret;
    }
    
    public function offsetToPage($offset, $limit) {
        return ceil($offset/$limit)+1;
    }
}


