<?php

/**
 * Description of ConferenceListPage
 *
 * @author hussam
 */
class ConferenceListPage extends Page {

    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $conferences = Conference::getConferences();
        if(isset($conferences) && count($conferences)>0){
            $table = new HtmlTable();
            $table->setHead(["#","Name"]);
            foreach ($conferences as $id => $conference)
                $table->addRow([$id,'<a href="?page=Conference&conference='.$conference->conference_id.'">'.$conference->conference_name.'</a>']);
            $body->addToCenter($table);
        }else
            $body->addToCenter (new Message("There is no conference to show",  Message::INFO));
    }

}
