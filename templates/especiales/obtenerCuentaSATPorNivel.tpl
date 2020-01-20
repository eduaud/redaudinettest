<option value="">--Selecciona--</option>
{section name="cuenta" loop=$cuentasSAT}
	<option value="{$cuentasSAT[cuenta].0}">{$cuentasSAT[cuenta].1}</option>
{/section}