<?PHP 
		include ("../libreria/config.php");
		include ("../libreria/libreria.php");

		$conexion=conectarDB($Servidor,$UsrMysql,$ClaveMysql,$DB);

//Obtener Datos
						echo "	<chart>
								<license>LTQNN-HE4JUOASZ0B6SVMYWHM5SXBL</license>
								<chart_data>
						";

			  		//$arraytabla=array(array(0),array(0));
					$consulta= "SELECT abreviatura,descripcion FROM `cat_municipios`m, solicitud s WHERE m.`id_municipio`=s.`id_municipio`";
					$consulta.= " AND anio=".$_GET['anio'];
					$consulta.= " GROUP BY s.id_municipio";
//					$consulta= "SELECT abreviatura
//								FROM `cat_municipios` ORDER BY id_municipio";
			
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					
					if (mysql_num_rows($sql_datos)>0){	
//						$arraytabla[0][0]="AÑO";
						echo '
									<row>
										<null/>	';
						while ($proyectos=mysql_fetch_array($sql_datos)){
							echo '		
									<string>'.$proyectos['descripcion'].'</string>';
//							$arraytabla[0][$proyectos['id_tipo']]=;
						}
						mysql_free_result($sql_datos);
						echo '
									</row>	';
					}
					
					$consulta= "SELECT count(id_solicitud) AS cantidad, descripcion FROM `cat_municipios`m, solicitud s WHERE m.`id_municipio`=s.`id_municipio`";
					$consulta.= " AND anio=".$_GET['anio'];
					$consulta.= " GROUP BY s.id_municipio";
					$sql_datos=mysql_query($consulta,$conexion) or die ("La consulta:<br>".$consulta."<br>No se realiz&oacute; correctamente.<br>Copie la sentencia y consulte con el administrador del sistema");
					if (mysql_num_rows($sql_datos)>0){	
								echo "
									<row>	
										<null/>	";
						while ($anios=mysql_fetch_array($sql_datos)){
							//$arraytabla[$cont][0]=$anios['anio'];
							echo "
									<number tooltip='".$anios['descripcion']."'>".$anios['cantidad']."</number>";
						}
						mysql_free_result($sql_datos);
								echo "
									</row>	";
					}
						echo "
								</chart_data>
	<chart_grid_h thickness='0' />
							";
	if ($_GET['tipo']=="true"){
		echo "<chart_label shadow='low' color='0' alpha='65' size='10' position='inside' as_percentage='true' />";
	}else{
		echo "<chart_label shadow='low' color='0' alpha='65' size='10' position='inside' as_percentage='false' />";
	}
						echo "							
	<chart_pref select='false' drag='true' rotation_x='60' min_x='20' max_x='90' />
	<chart_rect x='260' y='40' width='350' height='250' positive_alpha='0' />
	<chart_transition type='spin' delay='0' duration='1' order='category' />
	<chart_type>3d pie</chart_type>
	<tooltip color='FFFFFF' alpha='90' background_color_1='8888FF' background_alpha='90' shadow='medium' />

	<draw>
		<rect bevel='bg' layer='background' x='0' y='0' width='400' height='320' fill_color='ffffff' line_thickness='0' />
		<text shadow='low' color='9E0D05' alpha='60' size='27' x='0' y='15' width='430' height='50' v_align='middle'>Constancias de Compatibilidad de 2011 por Municipio</text>
		<rect shadow='low' layer='background' x='-50' y='70' width='600' height='212' rotation='-5' fill_alpha='0' line_thickness='80' line_alpha='5' line_color='D6D6D6' />
	</draw>
	<filter>
		<shadow id='low' distance='2' angle='45' color='0' alpha='50' blurX='4' blurY='4' />
		<bevel id='bg' angle='180' blurX='100' blurY='100' distance='50' highlightAlpha='0' shadowAlpha='15' type='inner' />
		<bevel id='bevel1' angle='45' blurX='5' blurY='5' distance='1' highlightAlpha='25' highlightColor='ffffff' shadowAlpha='50' type='inner' />
	</filter>
	
    <context_menu save_as_bmp='true' save_as_jpeg='true' save_as_png='true' /> 
<legend shadow='low' bevel='bevel1' transition='dissolve' delay='5' duration='0.5' x='0' y='75' width='50' height='5' layout='horizontal' margin='5' bullet='line' size='13' color='9E0D05' alpha='75' fill_color='ffffff' fill_alpha='10' line_color='ffffff' line_alpha='0' line_thickness='0' />

	<series_color>
		<color>00ff88</color>
		<color>ffaa00</color>
		<color>66ddff</color>
		<color>bb00ff</color>
	</series_color>
	<series_explode>
		<number>25</number>
		<number>45</number>
	</series_explode>

</chart>
						";

?>
