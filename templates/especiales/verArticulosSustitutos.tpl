{if $tipoCarga eq "1"}
{include file="_header.tpl" pagetitle="$contentheader"}
<link href="{$rooturl}/css/pop_up_form.css" rel="stylesheet" type="text/css" />

{/if}    
<!--<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-1.9.1.min.js'></script>
<script src='{$rooturl}/js/fullcalendar-1.6.1/jquery/jquery-ui-1.10.2.custom.min.js'></script>-->
<script language="javascript" src="{$rooturl}/js/jquery-1.8.3.js"></script>

<link rel="stylesheet" type="text/css" href="{$rooturl}/css/thickbox.css" media="screen"/>

 <link href="{$rooturl}css/style-t.css" rel="stylesheet" type="text/css" />

   
{literal}
 <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js?ver=1.4.4'></script>
                <script type="text/javascript" src="../../js/jMyCarousel.min.js"></script>
                <script type="text/javascript"></script>
				<script>
                $(document).ready(function() {
                    $(".jMyCarousel").jMyCarousel({ // Script de los Thumbnails
                    visible: '100%',
                    eltByElt: true
                    });
                    $(".jMyCarousel img").fadeTo(100, 0.6);
                    $(".jMyCarousel a img").hover(
					function(){ //mouse over
						$(this).fadeTo(400, 1);
					},
					function(){ //mouse out
						$(this).fadeTo(400, 0.6);
					});
                });
                </script>
<script type="text/javascript">
  $(document).ready(function(){ // Script de la Galeria
    $('#contenido_galeria div').css('position', 'absolute').not(':first').hide();
    $('#contenido_galeria div:first').addClass('aqui');
    $('.jMyCarousel a').click(function(){
        $('#contenido_galeria div.aqui').fadeOut(400);
        $('#contenido_galeria div').removeClass('aqui').filter(this.hash).fadeIn(400).addClass('aqui');
        return false;
    });
 });
</script>     
{/literal}

    <br />
	<br />
	



















    <div class="contenedor grupo"  >
      <h1>Articulo:   {$nombre}</h1>
      <div class="contenido" >
	     
         <br /><br />
         <div class="clear"></div>
         <div id="contenido_galeria" align="">
             {$cadenaImagenPrincipal}
         </div><!-- fin div contenido-galeria -->
         <div class="jMyCarousel">
            <ul >
                {$cadenaImagenMini}
			</ul>
         </div>
        <!-- Fin div jMyCarousel -->
   </div><!-- Fin div contenido -->
   <div class="clear"></div>
 </div>
