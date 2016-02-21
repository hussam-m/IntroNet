<?php
require_once 'Component.php';
/**
 * Description of newPHPClass
 *
 * @author hussam
 */
class HtmlTable extends Component {
    
    //private $number_of_columns;
    private $rows=[];
    private $head=[];

//    public function __construct($number_of_columns) {
//        $this->number_of_columns=$number_of_columns;
//    }
    
    public function addRow(Array $cells){
        $this->rows[]=$cells;
    }
    
    public function setHead(Array $cells){
        $this->head=$cells;
    }

    public function build() {
        echo '<div class="table-responsive"><table class="table table-striped table-hover table-bordered">';
        if(count($this->head)>0){
            echo '<tr>';
            foreach ($this->head as $column){
                echo '<th>';
                echo $column;
                echo '</th>';
            }
            echo '</tr>';
        }
        foreach($this->rows as $row){
            echo '<tr>';
            foreach ($row as $column){
                echo '<td>';
                echo $column;
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table></div>';
    }
}
