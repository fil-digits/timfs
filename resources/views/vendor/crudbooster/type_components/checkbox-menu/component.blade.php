<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
	<label class='control-label col-sm-4'>{{$form['label']}} {!!($required)?"<span class='text-danger' title='This field is required'>*</span>":"" !!}</label>							
	<div class="{{$col_width?:'col-sm-8'}}">

	@if($form['dataenum']!='')
		<?php 
			@$value = explode(";",$value);
			@array_walk($value, 'trim');
			$dataenum = $form['dataenum'];
			$dataenum = (is_array($dataenum))?$dataenum:explode(";",$dataenum);
		?>
		@foreach($dataenum as $k=>$d)
			<?php 
				if(strpos($d, '|')){
					$val = substr($d, 0, strpos($d, '|'));
					$label = substr($d, strpos($d, '|')+1);
				}else{

					$val = $label = $d;

				}
				$checked = ($value && in_array($val, $value))?"checked":"";									
			?>
			<div class="checkbox {{$disabled}}">
				<label>
				<input type="checkbox" {{$disabled}} {{$checked}} name="{{$name}}[]" value="{{$val}}"> {{$label}}								    
				</label>
			</div>
		@endforeach
	@endif

		<?php 
			if(@$form['datatable']):
		
				$datatable_array = explode(",",$form['datatable']);
				$datatable_tab = $datatable_array[0];
				$datatable_field = $datatable_array[1];
				$datatable_col = $datatable_array[2];
			
				//new
				$datatable_orderBy = $datatable_array[2];

				$tables = explode('.',$datatable_tab);
				$selects_data = DB::table($tables[0])->select($tables[0].".id");	

				$segmentation_data = DB::table('sku_legends')->where('status','ACTIVE')->get();	

				//$segmentation_list = DB::table('segmentations')->where('status','ACTIVE')->get();

				if(\Schema::hasColumn($tables[0],'deleted_at')) {
					$selects_data->where('deleted_at',NULL);
				}

				if(@$form['datatable_where']) {
					$selects_data->whereraw($form['datatable_where']);
				}

				if(count($tables)) {
					for($i=1;$i<=count($tables)-1;$i++) {
						$tab = $tables[$i];
						$selects_data->leftjoin($tab,$tab.'.id','=','id_'.$tab);
					}
				}																			

				$selects_data->addselect($datatable_field)->addselect($datatable_col);				

				//$selects_data = $selects_data->orderby($datatable_field,"asc")->get(); 
				//new
				$selects_data = $selects_data->orderby('segment_column_description',"asc")->get();

				if($form['relationship_table']){		
					$foreignKey = CRUDBooster::getForeignKey($table,$form['relationship_table']);	
					$foreignKey2 = CRUDBooster::getForeignKey($datatable_tab,$form['relationship_table']);																																

					$value = DB::table($form['relationship_table'])->where($form['relationship_table'].'.'.$foreignKey,$id);										
					$value = $value->pluck($foreignKey2)->toArray();											
					
					foreach($selects_data as $d) {																									
						$checked = (is_array($value) && in_array($d->id, $value))?"checked":"";										
						echo "
						<div data-val='$val' class='checkbox $disabled'>
							<label>
							<input type='checkbox' $disabled $checked name='".$name."[]' value='".$d->id."'> ".$d->{$datatable_field}."								    
							</label>
						</div>";
					}
				
				}else{
					@$value = explode(';',$value);
					$counter= 0;
					foreach($selects_data as $d) {		
						$counter++;										
						$val = $d->{$datatable_field};			
						//$checked = (is_array($value) && in_array($val, $value))?"checked":"";						
						if($val == '' || !$d->id) continue;
						
							?>
									<div class="{{$col_width?:'col-sm-10'}}">
							<?php
						
							echo "
							
							<div data-val='$val' class='checkbox $disabled'>
								<label>
									<strong>
										<p>	$val </p>
									</strong>
								";
							foreach($segmentation_data as $segment){
						
										$checked = (DB::table('menu_item_approvals')->where('id',$id)->value($d->segment_column_name) == $segment->sku_legend)?"checked":"";	
								
										echo "
											<label>
													<input type='radio' $disabled $checked name='$d->segment_column_name' value='$segment->sku_legend' required>&nbsp;$segment->sku_legend
											</label>
										<br/>";
				
							}
							echo "</div> ";
						
								?>

									</div>
									
								<?php
							
						
					}
				}
				
			endif;
			
					if($form['dataquery']){
			
						$query = DB::select(DB::raw($form['dataquery']));
						@$value = explode(';',$value);
						if($query) {
							foreach($query as $q){
								$val = $q->value;			
								$checked = (is_array($value) && in_array($val, $value))?"checked":"";						
								//if($val == '' || !$d->id) continue;
											echo "
						<div data-val='$val' class='checkbox $disabled'>
							<label>
							<input type='checkbox' $disabled $checked name='".$name."[]' value='$q->value'> ".$q->label." 								    
							</label>
						</div>";
							}
						}
				}
		?>
	<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
	<p class='help-block'>{{ @$form['help'] }}</p>
	</div>
</div>