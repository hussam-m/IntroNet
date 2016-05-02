<?php

/**
 * EventListPage shows a list of events that was added by the Planner
 * @category Page
 * @author hussam
 */
class EventListPage extends Page {
    const UserType = "Planner";
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Event List";

        // show a list of events on a table
        //$events = Database::get("Event");
        $events = Event::getEvents();
        if(isset($events) && count($events)>0){
//            $table = new HtmlTable();
//            $table->setHead(["#","Name","Start Date","End Date","Type"]);
//            foreach ($events as $id => $event)
//                $table->addRow([$id,'<a href="?page=Event&event='.$event->event_id.'">'.$event->name.'</a>',$event->getStartDate(),$event->getEndDate(),$event->getType()]);
//            $body->addToCenter($table);
            $body->addToCenter(new CustomHTML($this->pageHTML($events)));
        }else
            $body->addToCenter (new Message("There is no event to show",  Message::INFO));
        
        
    }
    
    private function pageHTML($events) {
        $conferences = Conference::getConferences();
        ob_start();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.css" >
        <form>
            <div id="toolbar" class="btn-group">
                <a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Add New Participants" href="index.php?page=NewEvent">
                    <i class="fa fa-plus"></i> New Event
                </a>
                <!--                <div class="dropdown">-->
                <button class="btn btn-default dropdown-toggle" type="button" id="filterMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="muted">Conference:</span>
                    <div style="display: inline"> All </div>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <li><a href="#" onclick="filter(-1, this)">All</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">Filter by conference</li>
                    <?php foreach($conferences as $id => $conference): ?>
                        <li><a href="#" onclick="filter(<?=$conference->id ?>, this)"><?=$conference->name?></a></li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <table data-toggle="table"
                   data-search="true"
                   data-show-toggle="true"
                   data-pagination="true"
                   data-show-columns="true"
                   data-detail-formatter="detailFormatter"
                   data-toolbar="#toolbar"
                   data-click-to-select="true"
                   data-select-item-name="participants"
                   >
                <thead>
                    <tr>
                        <th data-switchable="false" data-sortable="true">Name</th>
                        <th data-switchable="false" data-sortable="true" data-field="conference">Conference</th>
                        <th data-sortable="true">Start Date</th>
                        <th data-sortable="true">Start Time</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($events as $id => $event): ?>
                    <tr id="tr-id-<?=$id?>" class="tr-class-<?=$id?>">
                        <td id="td-id-<?=$id?>" class="td-class-<?=$id?>">
                            <a href="?page=Event&event=<?=$event->event_id?>"><?=$event->name?></a>
                        </td>
                        <td><?=$event->getConferenceName()?></td>
                        <td><?=$event->getStartDate()?></td>
                        <td><?=$event->getEndDate()?></td>
                        <td><?=$event->getType()?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                
            </table>
        </form>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
        <script>
            function filter(i, o) {
                $("#filterMenu div").text($(o).text());

                switch (i) {
                    <?php foreach($conferences as $id => $conference): ?>
                    case <?=$conference->id ?>:
                        $("table").bootstrapTable('filterBy', {conference: "<?=$conference->name?>"});
                        break;
                    <?php endforeach; ?>
                    default :
                        $("table").bootstrapTable('filterBy', {});
                }
            }
            
        </script>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header navbar-default ">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Send Invitation</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="emails">Email addresses</label>
                                <input type="text" class="form-control" name="emails" id="emails" disabled>
                            </div>
                            <div class="form-group">
                                <label for="message">Message content</label>
                                <textarea class="form-control" name="message" id="message" placeholder="message" rows="5"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer navbar-default">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button>
                        <button type="button" class="btn btn-default" onclick="preview()"><i class="glyphicon glyphicon-eye-open"></i> Preview</button>
                        <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-send"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header navbar-default ">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="previewModalLabel">Preview</h4>
                    </div>
                    <div class="modal-body">
                        <div class="jumbotron">
                            <div id="messageContent" style="margin-bottom: 15px; font-size: large;">

                            </div>
                            <button type="button" class="btn btn-info btn-lg center-block">Get Your Invitation</button> 
                        </div>
                    </div>
                    <div class="modal-footer navbar-default">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }

}
