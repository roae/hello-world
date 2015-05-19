<? #$this->Html->script("admin/locations",false);
/* @var $this View */
$this->Html->script("ext/bootstrap-datepicker",false);
$this->Html->script("admin/locations.min",false);
?>
<fieldset class="locations" >
	<legend>[:Movie_locations:]</legend>
	[:Movie_select_locations:]
	<?
	$movieLocation = $this->data['MovieLocation'];
	$this->data['MovieLocation'] = array();
	$index = 0;
	foreach($locations as $id=>$location){
		$founded = array(
			'premier_end'=>null,
			'presale_start'=>null,
			'presale_end'=>null
		);
		foreach((array) $movieLocation as $record){
			if(isset($record['location_id']) && $record['location_id'] == $id){
				$founded = $record;
				break;
			}
		}
		$this->data['MovieLocation'][$index] = $founded;


	?>
		<div class="location">
			<?
			if(isset($this->data['MovieLocation'][$index]['id'])){
				echo $this->Form->hidden("MovieLocation.$index.id",array('value'=>$this->data['MovieLocation'][$index]['id']));
			}
			?>
			<!-- complejo -->
			<div class="checkbox">
				<label for="MovieLocation<?= $index ?>LocationId">
					<input type="checkbox" id="MovieLocation<?= $index ?>LocationId" value="<?= $id ?>" name="data[MovieLocation][<?= $index ?>][location_id]" class="locationCheckbox" <?= (isset($this->data['MovieLocation'][$index]['location_id'])  )? "checked" : ""?>/>
					<?= $location?>
				</label>
			</div>
				<!-- estreno -->
			<? $invalid = isset($this->validationErrors['MovieLocation'][$index]['premiere_end']); ?>
				<div class="premiere">
					<div class="input text <?= $invalid? "error" : ""?>">
						<label for="MovieLocation<?= $index ?>PremiereEnd">[:select-premier-end-date:]</label>
						<?
						$premier_end = ( isset($this->data['MovieLocation'][$index]['premiere_end']) && !preg_match('/0{4}-0{2}-0{2}/',$this->data['MovieLocation'][$index]['premiere_end']))? $this->data['MovieLocation'][$index]['premiere_end'] : false;
						?>
						<input
							type="text"
							id="MovieLocation<?= $index ?>PremiereEnd"
							class="premiereEndDate"
							data-provide="datepicker"
							data-autoclose="true"
							data-todayHighlight="true"
							data-date-format="dd/M/yyyy"
							data-clear-btn="[:clear:]"
							<?= ($premier_end? 'value="'.($premier_end ? $this->Time->format("d/M/Y",$premier_end) : "").'"' : "")?>
						/>
						<input
							type="hidden"
							name="data[MovieLocation][<?= $index ?>][premiere_end]"
							<?= ($premier_end ? 'value="'.$premier_end.'"' : "")?>
						/>
					</div>
					<button type="button" class="btn"><i class="icon-calendar"></i></button>
				</div>
				<!-- proximamente -->
				<div class="commingSoon">
					<div class="checkbox">
						<input type="checkbox" class="commingSoonCheckbox" id="MovieLocation<?= $index ?>CommingSoon" value="1" name="data[MovieLocation][<?= $index ?>][comming_soon]" <?= (isset($this->data['MovieLocation'][$index]['comming_soon']) ? "checked" : "")?>/>
						<label for="MovieLocation<?= $index ?>CommingSoon">[:Movie_comming_soon:]</label>
					</div>
					<div class="premierDate">
						<div class="input text <?= $invalid? "error" : ""?>">
							<label for="MovieLocation<?= $index ?>PremiereDate">[:select-premier-date:]</label>
							<?
							$premier_date = ( isset($this->data['MovieLocation'][$index]['premiere_end']) && !preg_match('/0{4}-0{2}-0{2}/',$this->data['MovieLocation'][$index]['premiere_date']))? $this->data['MovieLocation'][$index]['premiere_date'] : false;
							?>
							<input
								type="text"
								id="MovieLocation<?= $index ?>PremiereDate"
								class="premiereDate"
								data-provide="datepicker"
								data-autoclose="true"
								data-todayHighlight="true"
								data-date-format="dd/M/yyyy"
								data-clear-btn="[:clear:]"
								<?= ($premier_date? 'value="'.($premier_date ? $this->Time->format("d/M/Y",$premier_date) : "").'"' : "")?>
								/>
							<input
								type="hidden"
								name="data[MovieLocation][<?= $index ?>][premiere_date]"
								<?= ($premier_date ? 'value="'.$premier_date.'"' : "")?>
								/>
						</div>
						<button type="button" class="btn"><i class="icon-calendar"></i></button>
					</div>
				</div>
				<!-- preventa -->
				<div class="presale">
					<div class="checkbox">
						<input type="checkbox" id="MovieLocation<?= $index ?>Presale" value="1" name="data[MovieLocation][<?= $index ?>][presale]" class="presaleCheckbox" <?= (isset($this->data['MovieLocation'][$index]['presale']) ? "checked" : "")?>/>
						<label for="MovieLocation<?= $index ?>Presale">[:Movie_presale:]</label>
					</div>
					<!-- periodo -->
					<div class="presale-daterange">
						[:select-presale-period:]
						<? $invalid = isset($this->validationErrors['MovieLocation'][$index]['presale_start']); ?>
						<div class="input-daterange input-group <?= $invalid ? "error" : "" ?>">
							<?
							$presale_start = ( isset($this->data['MovieLocation'][$index]['presale_start']) && !preg_match('/0{4}-0{2}-0{2}/',$this->data['MovieLocation'][$index]['presale_start']))? $this->data['MovieLocation'][$index]['presale_start'] : false;

							?>
							<input
								type="text"
								class="input-sm form-control"
								data-provide="datepicker"
								data-autoclose="true"
								data-todayHighlight="true"
								data-date-format="dd/M/yyyy"
								data-clear-btn="[:clear:]"
								<?= ($presale_start? 'value="'.($presale_start ? $this->Time->format("d/M/Y",$presale_start) : "").'"' : "")?>

							/>
							<input type="hidden"
							       name="data[MovieLocation][<?= $index ?>][presale_start]"
								<?= ($presale_start? 'value="'.$presale_start.'"' : "")?>
							/>
							<span class="add-on">a</span>
							<?
							$presale_end = ( isset($this->data['MovieLocation'][$index]['presale_end']) && !preg_match('/0{4}-0{2}-0{2}/',$this->data['MovieLocation'][$index]['presale_end']))? $this->data['MovieLocation'][$index]['presale_end'] : false;
							$invalid = isset($this->validationErrors['MovieLocation'][$index]['presale_end']);
							?>
							<input
								type="text"
								class="input-sm form-control <?= $invalid ? "error" : "" ?>"
								data-provide="datepicker"
								data-autoclose="true"
								data-todayHighlight="true"
								data-date-format="dd/M/yyyy"
								data-clear-btn="[:clear:]"
								<?= ($presale_end? 'value="'.($presale_end ? $this->Time->format("d/M/Y",$presale_end) : "").'"' : "")?>
								/>
							<input type="hidden"
							       name="data[MovieLocation][<?= $index ?>][presale_end]"
								<?= ($presale_end? 'value="'.$presale_end.'"' : "")?>
							/>
						</div>
					</div>

				</div>
		</div>
	<?
		/*if($this->data['MovieLocation'][$index]['location_id']==$id){
			$index++;
		}*/
		$index++;
	}
	?>
</fieldset>