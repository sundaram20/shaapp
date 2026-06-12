<div class="hidemenu">		
								
					<div id="kwbox2" class="col-md-2 kw-sidebar floating">
						<div class="kw-box">
						
							<table class="table table-responsive sidebar-h">
								<thead>
									<th width="90%">Menu Itemwise </th>
									<th>Qty</th>
									
								</thead>
							</table>
							
							<?php
							?>
							
							<table class="table table-responsive">
								<?php  
								
									foreach($itemlistArray as $Dataset=>$TableData1){
									
									
								?>
								<thead>
									<th colspan="2" class="text-center"><?php echo $Dataset; ?></th>
								</thead>
								
								<tbody>
								
								<?php foreach($TableData1 as $id_mst_items=>$value1){  ?>
									<tr>
										<td id="<?php echo $Mrow1->item_description; ?>" onclick="listItemWise(<?php echo $id_mst_items; ?>);" style="cursor:pointer;"><?php echo ucwords(strtolower($value1['item_description'])); ?></td>
										<td><span><?php echo round($value1['max_qty']); ?></span></td>
									</tr>
								
									<?php } ?>
									
								</tbody>
								
								<?php   } ?>
							</table>
							
						</div>
					</div>	
				</div>