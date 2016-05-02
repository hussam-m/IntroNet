<?php
/**
 * Description of TimerPage
 *
 * @author hussam
 */
class TimerPage extends Page{
    const UserType = "Planner";
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        
        // get the selected event or the one that is going to start soon
        if(isset($_POST['event']))
            $event= Event::getEvents("where event_id=".$_POST['event']);
        else   
            $event= Event::getEvents("where TIMESTAMP(`startDate`,`startTime`) > now() order by startDate, startTime LIMIT 0 , 1");
        $event= $event[0];
        
        // show a list of event to select from
        if(!isset($_POST['do'])){
            $body->addToCenter($this->page1($event));
            $form = new Form("Timer");
            $form->autoSubmit = TRUE;
            $form->keepData = TRUE;
            $form->addInput(Input::selectInput("event", "Event", Event::getEvents("where TIMESTAMP(`startDate`,`startTime`) > now() order by startDate, startTime")));
            $body->addToTop($form);
        }
        
        
        
        
    }
    public function callBack($data, $action, \PageBody &$body) {
        
        // get the selected event
        $event=  Event::getEvent($data['event']);
        if(isset($_POST['do']))
            $body->addToCenter($this->page2($event));
    }
    
    
    function page1($event) {
        ob_start();
        ?>
<div class="jumbotron">
  <h1><?=$event->name?></h1>
  <p>The event going to start soon</p>
  
      <form action="index.php?page=Timer" method="post">
          <div class="btn-group" role="group">
  <div class="btn btn-default disabled btn-lg"><div class="timer" data-date="<?=$event->getStartDate()?> <?=$event->getStartTime()?>"></div></div>
  <input type="submit" class="btn btn-primary btn-lg" role="button" value="Start Timer">
  </div>
  <input type="hidden" name="event" value="<?=$event->event_id?>">
  <input type="hidden" name="do" value="start">
      </form>
  
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.1.0/jquery.countdown.min.js"></script>
<script>
$(".timer").each(function( index ) {
    $(this).countdown( $(this).attr("data-date")  ,
        function(event) {
            $(this).text(
                event.strftime("%D days %H:%M:%S")
            );
        });
});
</script>
        <?php
        $html = ob_get_contents();
        ob_clean();
        return new CustomHTML($html);
    }
    
    function page2(Event $event) {
        $rounds = $event->rounds;
        $length = $event->roundLength;
        
        ob_start();
        ?>
<style>
/*    *{transition: all .5s;}*/
    .move{
        stroke-dasharray: 0,20000;
        animation: moving <?=$length*60?>s linear forwards;
    }
    .ending{
        fill: #000;
        animation-name: example;
        animation-duration: .5s;
        animation-direction: alternate;
        animation-iteration-count: infinite;
    }
    
    @keyframes example {
  to {
    fill: #F44336;
  }
}
    
    @keyframes moving {
  to {
    stroke-dasharray: 360,20000;
  }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/circliful/0.1.5/css/jquery.circliful.min.css" >

<div class="jumbotron">
<center>
<h1><?=$event->name?></h1>

<h2 id="round">Round 1</h2>

        <div class="row">
            <div class="col-md-offset-3 col-md-6 col-xs-12">
<div class="circliful"></div>
            </div>
        </div>
<h1 id="getting-started"></h1>

</center>  
</div>
<script src="https://cdn.rawgit.com/pguso/jquery-plugin-circliful/master/js/jquery.circliful.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.1.0/jquery.countdown.min.js"></script>
        <script>
            var time = 0;
            var length = <?=$length?>;
            $("nav").hide();
            $(".circliful").circliful({
                        //dimension: Math.min(window.screen.height,window.screen.width)*.5,
                        //percentageTextSize:50,
                        foregroundColor:"#0A85FF",
                        percent: time,
                        animation: 0
                    });
            $("svg")[0].setAttribute('viewBox', $("svg")[0].getAttribute('viewBox').replace(" 0 "," 30 "));
////            $(".timer").each(function( index ) {
//            $(this).countdown( $(this).attr("data-date")  ,
//                function(event) {
//                    $(this).text(
//                        event.strftime("%D days %H:%M:%S")
//                    );
//                });
//            });
//          $( document ).ready(function(){
//                setInterval(timer,1000);
//            });
//            function timer(){
//                $(".circliful").circliful({
//                        percent: ++time,
//                        animation: 0
//                    });
//            }
var round=1;
length=length*60000;
var l = length/1000;
$(".timer").attr("fill","#000");
$(".circle").attr("class","circle move");
$("#getting-started").countdown( Date.now()+length , function(event) {
     $(".timer").text(
       event.strftime('%M:%S')
     );
     var seconds = event.offset.seconds + event.offset.minutes*60
     
     //$(".circle").attr("stroke-dasharray",(((l-seconds)/l)*360)+",20000");
     //
     //console.log(  ((l-seconds)/l)*360 );
      if(seconds==15){
           //$(".timer").attr("fill","#f00");
           $(".timer").attr("class","timer ending");
           $(".circle").attr("class","circle move");  
       }
       
   }).on('finish.countdown', function(event) {
       round++;
       if(round><?=$rounds?>){
           $("#round").text("This event is over! Thank you");
           return;
       }
       
       $("#round").text("Time is up! Round "+round+" is starting soon")
       $(".circle").attr("class","circle stop");
       setTimeout(function(){ 
            //$(".timer").attr("fill","#000");
            $(".timer").attr("class","timer");
            
            $("#getting-started").countdown(Date.now()+length);
            $(".circle").attr("class","circle move");
            $("#round").text("Round "+round);
        }, 10000);   
   });
   window.onbeforeunload = function(){
    return "Warning: the timer will be reset if you refresh or leave the page!";
}
        </script>
        <?php
        $html = ob_get_contents();
        ob_clean();
        return new CustomHTML($html);
    }
    
    function page3($event) {
        ob_start();
        ?>

        <?php
        $html = ob_get_contents();
        ob_clean();
        return new CustomHTML($html);
    }
}
