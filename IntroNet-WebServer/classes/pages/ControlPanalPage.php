<?php

/**
 * Description of ControlPanalPage
 *
 * @author hussam
 */
class ControlPanalPage extends Page {
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $events = Event::getEvents("where TIMESTAMP(`startDate`,`startTime`) > now() order by startDate, startTime LIMIT 0 , 4");
        $html = '
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/circliful/0.1.5/css/jquery.circliful.min.css" >
                <script src="https://cdnjs.cloudflare.com/ajax/libs/circliful/0.1.5/js/jquery.circliful.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.1.0/jquery.countdown.min.js"></script>
                <div class="ControlPanal">
                ';
        foreach ($events as $key => $event) {
            $html .= '
                <div class="col-md-3" style="text-align: center;">
                    <div class="well">
                    <div class="row" style="font-size: xx-large;">
                        '.$event->getStartDay().'
                    </div>
                    <div class="row">
                        <div class="circliful" data-percent="'.$event->getNumberOfParticipantion().'" data-text="'.$event->getNumberOfParticipant().'" style="margin: auto;"></div>
                    </div>
                    <div class="row">
                        <div class="timer" data-date="'.$event->getStartDate().' '.$event->getStartTime().'"></div>
                    </div>
                    <div class="row">
                        <a class="btn btn-default" href="?page=Event&event='.$event->Event_id.'" role="button" style="width: 90%;">'.$event->name.'</a>
                    </div>
                    </div>
                </div>
                ';
        }
        $html .= '</div>
                    <script>
                    $(".circliful").circliful({
                        //dimension: "100%"
                        percentageTextSize:30,
                        foregroundColor:"#0A85FF",
                    });
                    $(".timer").each(function( index ) {
                    $(this).countdown( $(this).attr("data-date")  ,
                        function(event) {
                            $(this).text(
                                event.strftime("%D days %H:%M:%S")
                            );
                        });
                    });
                    
                    </script>
                 ';
        $body->addToCenter(new CustomHTML($html));
        
    }

}
