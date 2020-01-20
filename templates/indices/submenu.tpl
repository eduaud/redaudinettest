{include file="_header.tpl" pagetitle="$contentheader"}

<a href="" class="thickbox" id="thickbox_href"></a>  

<h1>{$nombre_menu}</h1>

 <div style="clear:both;">
	<ul class="bullets">
	
	{section loop=$registrossubmenu name=q}
					
		{*creara un li normal   es el que puede variar dependiendo de la pantalla *}
		{if $registrossubmenu[q][3] eq '.' }
			<li style="padding: 5px"> <p> <a href="{$rooturl}{$registrossubmenu[q][10]}{$registrossubmenu[q][0]}" >{$registrossubmenu[q][1]}</a> </p></li>
        {else}
			<li style="padding: 5px"> <p> <a href="{$rooturl}{$registrossubmenu[q][10]}{$registrossubmenu[q][3]}" >{$registrossubmenu[q][1]}</a> </p></li>				
		{/if}
				
                		
		{/section}
	</ul>
 </div>
		
{include file="_footer.tpl" aktUser=$username}
