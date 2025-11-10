<?php 
	$this->load->view('header');
?>


<style>
.ui-content { /* fons: haal de verticale scrollbalk weg, ergens is er een overflow geintroduceerd */
	overflow-y:hidden;
}
</style>

<script type="text/javascript" src="/js/charts.js"></script>	  

<script type="text/javascript">

        $(document).ready(function () {
			data_taartdiagram = <?php echo $vlaaidiagram['data']; ?>;
			
            var settings = {
                title: "Uren <?php echo Date('Y'); ?>",
				description: "",
                enableAnimations: true,
                showLegend: true,
                showBorderLine: true,
                legendLayout: { left: 10, top: 160, width: 300, height: 200, flow: 'vertical' },
                //padding: { left: 70, top: 5, right: 5, bottom: 5 },
				padding: { left: 120, top: 5, right: 5, bottom: 5 },
                titlePadding: { left: 0, top: 5, right: 0, bottom: 10 },
  		        source: data_taartdiagram,
                colorScheme: 'scheme03',
                seriesGroups:
                    [
                        {
                            type: 'pie',
                            showLabels: true,
							toolTipFormatFunction: toolTipCustomFormatFn,
                            series:
                                [
                                    { 
                                        dataField: 'Share',
                                        displayText: 'Uren',
                                        //labelRadius: 170,
										labelRadius: 140,
                                        initialAngle: 15,
                                        //radius: 145,
										radius: 115,
                                        centerOffset: 0,
                                        formatFunction: function (value) {
                                            if (isNaN(value))
                                                return value;
                                            return parseFloat(value) + '%';
                                        },
                                    }
                                ]
                        }
                    ]
            };
            $('#vlaaiContainer').jqxChart(settings);
        });
    </script>

    <script type="text/javascript">
	
        $(document).ready(function () {
				
			data_staafdiagram = <?php echo $staafdiagram["data"]; ?>;

            var settings = {
                title: "Omzet <?php echo $staafdiagram['vorigjaar'] . " - " . $staafdiagram['ditjaar']?>",
				description: "",
                enableAnimations: true,
                showLegend: true,
				showToolTips: true,
                padding: { left: 5, top: 5, right: 5, bottom: 5 },
				//legendLayout: { left: 10, width: 300, height: 200, flow: 'horizontal' },
                titlePadding: { left: 50, top: 5, right: 0, bottom: 10 },
                source: data_staafdiagram,
                xAxis:
                    {
                        dataField: 'Jaar',
                        unitInterval: 1,
                        axisSize: 'auto',
                        tickMarks: {
                            visible: true,
                            interval: 1,
                            color: '#BCBCBC'
                        },
                        gridLines: {
                            visible: true,
                            interval: 1,
                            color: '#BCBCBC'
                        }
                    },
                valueAxis:
                {
                    //unitInterval: 10000,
					unitInterval: <?php echo $staafdiagram["interval"]; ?>,
                    minValue: 0,
                    //maxValue: 100000,
					maxValue: <?php echo $staafdiagram["max"]; ?>,
                    title: { text: 'Omzet' },
                    labels: { horizontalAlignment: 'right' },
                    tickMarks: { color: '#BCBCBC' }
                },
                colorScheme: 'scheme03',
                seriesGroups:
                    [
                        {
                            type: 'stackedcolumn',
                            columnsGapPercent: 50,  
                            seriesGapPercent: 0,   
							toolTipFormatFunction: toolTipCustomFormatFnBanketstaaf,
							series: <?php echo $staafdiagram["legenda"]; ?>
                        }
                    ]
					
            };
            // setup the chart
            $('#staafContainer').jqxChart(settings);

        });
    </script>


	<div id="page-heading">
		<h1><?php echo $company_name; ?></h1>
	</div>

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<div id="content-table-inner">
		
			<div id="table-content">
			<h2>Welkom bij Coop & Co</h2>

			<div style="float:left;" >			
				<div id='vlaaiContainer' style="width: 550px; height:450px;" >
				</div>
			</div>					
			<div style="float:left;" >			
				<div ZZstyle="float:left;" id='staafContainer' style="width: 550px; height:450px;" >
				</div>
			</div>		

			</div>
	
			<div class="clear"></div>
			
		 
		</div>
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

</div>

<div class="clear">&nbsp;</div>
</div>


<div class="clear">&nbsp;</div>





<?php $this->load->view('footer');?>
