<?php

/**
 * Description of ParticipantListPage
 *
 * @author hussam
 */
class ParticipantListPage extends Page {

    const UserType = "Planner";

    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $conference = Conference::getConferences();
        $form = new Form("ParticipantList");
        if (!isset($_POST['conference']))
            array_unshift($conference, array("-1", "Select a conference"));
        $form->addInput(Input::selectInput("conference", "Select a Conference", $conference));
        $form->autoSubmit = TRUE;
        $form->keepData = TRUE;
        $body->addToTop($form);
        //var_dump($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]);
    }

    public function callBack($data, $action, \PageBody &$body) {
//        $table = new HtmlTable();
//        $table->setHead(["First Name","Last Name","Organisation"]);
//        $participants = Participant::getParticipants(0);
//        //$body->addToCenter(new Message(var_dump($participants)));
//        foreach ($participants as $key => $participant) {
//            $table->addRow([$participant->fname,$participant->lname,$participant->organisation]);
//        }
//        $body->addToCenter($table);
        if (isset($data['conference']))
            $body->addToCenter(new CustomHTML($this->pageHTML($data)));
    }

    private function pageHTML($data) {
        ob_start();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.css" >
        <form>
            <div id="toolbar" class="btn-group">
                <a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Add New Participants" href="index.php?page=insertParticipant">
                    <i class="fa fa-user-plus"></i>
                </a>
                <button type="button" class="btn btn-default" id="invitationBT" data-toggle="tooltip" data-placement="top" title="Send Invitation" onclick="send()">
                    <i class="glyphicon glyphicon-send"></i>
                    <span>Send Invitation</span>
                </button>
                <!--                <div class="dropdown">-->
                <button class="btn btn-default dropdown-toggle" type="button" id="filterMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="muted">View</span>
                    <div style="display: inline"> All </div>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <li><a href="#" onclick="filter(0, this)">All</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="filter(1, this)">Invited</a></li>
                    <li><a href="#" onclick="filter(2, this)">Not yet invited</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="filter(3, this)">Registered</a></li>
                    <li><a href="#" onclick="filter(4, this)">Not yet registered</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="filter(5, this)">Has schedule</a></li>
                    <li><a href="#" onclick="filter(6, this)">Does not schedule</a></li>
                </ul>
                <!--                </div>-->
                                <!--    <select class="form-control">
                                        <option>View all</option>
                                        <option>Invited</option>
                                        <option>Not yet invited</option>
                                        <option>Registered</option>
                                        <option>Not yet registered</option>
                                    </select>-->
            </div>
            <table data-toggle="table"
                   data-url="index.php/data/conference?get=participants&id=<?= $data['conference'] ?>"
                   data-search="true"
                   data-show-toggle="true"
                   data-pagination="true"
                   data-show-columns="true"
                   data-detail-view="true"
                   data-detail-formatter="detailFormatter"
                   data-toolbar="#toolbar"
                   data-click-to-select="true"
                   data-select-item-name="participants"
                   >
                <thead>
                    <tr>
                        <th data-field="selected" data-checkbox="true"></th>
                        <th data-field="fname" data-switchable="false" data-sortable="true">First Name</th>
                        <th data-field="lname" data-switchable="false" data-sortable="true">Last Name</th>
                        <th data-field="organisation" data-sortable="true">Organisation</th>
                        <th data-field="phone">Phone</th>
                        <th data-field="email">Email</th>
                        <th data-field="invitation" data-halign="center" data-align="center" ><i class="glyphicon glyphicon-send" data-toggle="tooltip" data-placement="top" title="Invited"></i></th>
                        <th data-field="registered" data-halign="center" data-align="center"><i class="glyphicon glyphicon-check" data-toggle="tooltip" data-placement="top" title="Registered"></i></th>
                        <th data-field="hasSchedule" data-halign="center" data-align="center"><i class="fa fa-table" data-toggle="tooltip" data-placement="top" title="Has Schedule"></i></th>
                        <th data-field="disabled" data-halign="center" data-align="center"><i class="fa fa-wheelchair" data-toggle="tooltip" data-placement="top" title="Handicapped"></i></th>
                    </tr>
                </thead>
            </table>
        </form>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
        <script>
            $("table").on("all.bs.table", function () {
                if ($("table").bootstrapTable("getSelections").length == 0)
                    $("#invitationBT").prop("disabled", true);
                else
                    $("#invitationBT").prop("disabled", false);
            });
            function preview() {
                if ($("#message").val() == "")
                    $("#messageContent").text("You have been invited to " + $("#conference option:selected").text());
                else
                    $("#messageContent").text($("#message").val());
                $("#previewModal").modal("show");
            }
            function send(e) {
                var emails = "";
                if(e)
                    emails = e;
                else
                {
                    $($("table").bootstrapTable("getSelections")).each(function () {
                        emails += this.email + ", ";
                        //console.log(emails);
                    });
                }
                $("#emails").val(emails);
                $("#myModal").modal("show");
            }
            function filter(i, o) {
                $("#filterMenu div").text($(o).text());

                switch (i) {
                    case 1:
                        $("table").bootstrapTable('filterBy', {invitation: "✓"});
                        break;
                    case 2:
                        $("table").bootstrapTable('filterBy', {invitation: ""});
                        break;
                    case 3:
                        $("table").bootstrapTable('filterBy', {registered: "✓"});
                        break;
                    case 4:
                        $("table").bootstrapTable('filterBy', {registered: ""});
                        break;
                    case 5:
                        $("table").bootstrapTable('filterBy', {hasSchedule: "✓"});
                        break;
                    case 6:
                        $("table").bootstrapTable('filterBy', {hasSchedule: ""});
                        break;
                    default :
                        $("table").bootstrapTable('filterBy', {});
                }
            }
            function detailFormatter(index, row) {
                var html = [];
//                $.each(row, function (key, value) {
//                    html.push('<p><b>' + key + ':</b> ' + value + '</p>');
//                });
                //html.push('<div class="well">');
                html.push('<p><b>Name:</b> '+row['fname']+' '+row['lname']+'</p>');
                if(row['phone']!=null)
                    html.push('<p><b>Phone:</b> '+formatPhone(row['phone'])+'</p>');
                html.push(' <a type="button" class="btn btn-primary" href="?page=AssignWeight&p='+row['participant_id']+'"><i class="fa fa-sort"></i> <span>Change Weight</span></a>');
                html.push(' <button type="button" class="btn btn-primary" onclick="send(\''+row['email']+'\')"><i class="glyphicon glyphicon-send"></i> <span>Send Invitation</span></button>');
                html.push(' <a type="button" class="btn btn-primary '+((row['hasSchedule']=='')?'disabled':'')+'" href="?page=schedule&p='+row['participant_id']+'"><i class="fa fa-table"></i> <span>View Schedule</span></a>');
                
                //html.push('</div>');
                
                return html.join('');
            }
            
            //http://jsfiddle.net/kaleb/Dm4Jv/
            function formatPhone(n) {
                var numbers = n.replace(/\D/g, ''),
                    char = {0:'(',3:') ',6:' - '};
                n = '';
                for (var i = 0; i < numbers.length; i++) {
                    n += (char[i]||'') + numbers[i];
                }
                return n;
            }
            
            function sendInvitation(p){
                var participants = "";
                if(p)
                    participants = p;
                else
                {
                    participants = [];
                    $($("table").bootstrapTable("getSelections")).each(function () {
                        participants.push(this.participant_id);
                        //console.log(emails);
                    });
                    participants = participants.toString();
                }
                $("#emails").val(emails);
                $("#myModal").modal("hide");
                
                $.post( "<?=$_SERVER["PHP_SELF"]."/send"?>", { participants: participants, message:$("#message").val() })
                    .done(function( data ) {
                      alert( "Data Loaded: " + data );
                });
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
                        <button type="button" class="btn btn-default" onclick="sendInvitation()"><i class="glyphicon glyphicon-send"></i> Send</button>
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
