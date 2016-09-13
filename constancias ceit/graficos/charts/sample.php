<?PHP 
		include ("../../libreria/config.php");
		include ("../../libreria/libreria.php");

		$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

//Obtener Datos
						echo "	<chart>
								<axis_category size='16' alpha='85' shadow='medium' />
								<axis_category shadow='shadow1' size='12' color='000000' alpha='50' />
								<axis_ticks value_ticks='false' category_ticks='true' major_thickness='2' minor_thickness='1' minor_count='1' minor_color='222222' position='inside' />
								<axis_value shadow='medium' min='-1' size='10' color='9E0D05' alpha='65' steps='6' show_min='false' />
								<chart_data>
						";

			  		//$arraytabla=array(array(0),array(0));
					$consulta= "SELECT abreviatura
								FROM `cat_tipo_proy` 
								WHERE id_tipo IN (SELECT DISTINCT (id_proyecto1) FROM `solicitud`)";
			
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					
					if (mysql_num_rows($sql_datos)>0){	
//						$arraytabla[0][0]="AÑO";
						echo '
									<row>
										<null/>	';
						while ($proyectos=mysql_fetch_array($sql_datos)){
							echo '		
									<string>'.$proyectos['abreviatura'].'</string>';
//							$arraytabla[0][$proyectos['id_tipo']]=;
						}
						mysql_free_result($sql_datos);
						echo '
									</row>	';
					}
					
					$consulta= "SELECT DISTINCT (anio) FROM `solicitud`";
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					if (mysql_num_rows($sql_datos)>0){	
						$cont=1;
						while ($anios=mysql_fetch_array($sql_datos)){
							//$arraytabla[$cont][0]=$anios['anio'];
							echo '
									<row>
									<string>'.$anios['anio'].'</string>';
							$consulta= "SELECT DISTINCT (id_proyecto1), count( id_proyecto1 ) AS numero
										FROM `solicitud` WHERE anio=".$anios['anio']."
										GROUP BY `id_proyecto1` 
										ORDER BY `id_proyecto1` ASC 
										";
							$sql_proys=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
							while ($proyectos=mysql_fetch_array($sql_proys)){
								//$arraytabla[$cont][$proyectos['id_proyecto1']]=$proyectos['numero'];
/*								echo "
										<number shadow='medium' tooltip='$48 Million'>48</number>
								";
*/								echo "
										<number tooltip='".$proyectos['numero']."'>".$proyectos['numero']."</number>";
								
							}
							mysql_free_result($sql_proys);
							$cont++;
								echo "
									</row>	";
						}
						mysql_free_result($sql_datos);
					}
						echo '
								</chart_data>
							';
						echo "							
	<chart_grid_h alpha='10' thickness='1' />
	<chart_label color='ddffff' alpha='90' size='10' position='middle' />
	<draw>
		<text shadow='shadow1' color='9E0D05' alpha='75' rotation='0' size='25' x='0' y='20' width='600' height='200' h_align='center' v_align='top'>Comparativo Anual de Constancias</text>
	</draw>

	<chart_guide horizontal='true' vertical='true' thickness='1' alpha='25' type='dashed' text_h_alpha='0' text_v_alpha='0' />
	<chart_note type='flag' size='11' color='000000' alpha='70' x='-10' y='-36' background_color_1='ffffaa' background_color_2='ccff00' background_alpha='90' shadow='medium' bevel='note' />
	<chart_pref line_thickness='2' point_shape='circle' point_size='7' fill_shape='false' />
	<chart_rect x='90' y='100' width='420' height='250' positive_color='ffffee' positive_alpha='65' negative_color='ff8888' negative_alpha='65' bevel='bg' shadow='high' />
	<chart_transition type='slide_left' delay='.5' duration='1' order='series' />
<filter>
		<shadow id='shadow1' distance='2' angle='45' color='0' alpha='40' blurX='5' blurY='5' />
		<shadow id='low' distance='2' angle='45' alpha='20' blurX='5' blurY='5' />
		<shadow id='medium' distance='2' angle='45' alpha='40' blurX='7' blurY='7' />
		<shadow id='high' distance='5' angle='45' alpha='25' blurX='10' blurY='10' />
		<bevel id='bg' angle='45' blurX='15' blurY='15' distance='5' highlightAlpha='25' shadowAlpha='50' type='outer' />
		<bevel id='note' angle='45' blurX='10' blurY='10' distance='3' highlightAlpha='60' shadowAlpha='15' />
	</filter>
<legend shadow='low' transition='dissolve' delay='0' duration='0.5' x='90' y='75' width='420' height='5' layout='horizontal' margin='5' bullet='line' size='13' color='ffffff' alpha='75' fill_color='000000' fill_alpha='10' line_color='000000' line_alpha='0' line_thickness='0' />

	
	<tooltip color='FFFFFF' alpha='90' background_color_1='8888FF' background_alpha='90' shadow='medium' />
    
    <context_menu save_as_bmp='true' save_as_jpeg='true' save_as_png='true' /> 
    
	<series_color>
		<color>528743</color>
		<color>9E0D05</color>
		<color>8844ff</color>
	</series_color>
	<series_explode>
		<number>400</number>
	</series_explode>

								</chart>
						";

?>
