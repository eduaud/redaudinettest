<select name="id_cuenta_superior" id="id_cuenta_superior" onChange="verificaObligatorioCuentaMayorNivel2($(this).val())">
	<option value="0"> -- Selecciona -- </option>
	{section name="cuentaSuperior" loop=$comboCuentaSuperior}
		<option value="{$comboCuentaSuperior[cuentaSuperior].0}"> {$comboCuentaSuperior[cuentaSuperior].1} </option>
	{/section}
</select>