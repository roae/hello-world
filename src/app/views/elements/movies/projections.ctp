<?php
$this->Html->script("admin/projections.min",false);
?>
<fieldset ng-controller="ProjectionsCtrl">
	<legend>[:Movie_projections:]</legend>
	<div class="projections">
			<table class="grid">
				<thead>
					<tr>
						<th>[:Projection_vista_code:]</th>
						<th>[:Projection_format:]</th>
						<th>[:Projection_lang:]</th>
						<th>-</th>
					</tr>
				</thead>
				<tbody>
					<tr class="projection" ng-repeat="projection in Projections">
						<td>
							<input type="hidden" name="data[Projection][{{ $index }}][id]" value="{{ projection.id }}">
							<div class="input text required" ng-class="{error: ValidationErrors.Projection[$index].vista_code}">
								<input type="text" name="data[Projection][{{ $index }}][vista_code]" value="{{ projection.vista_code }}"/>
							</div>
						</td>
						<td>
							<div class="input select required " ng-class="{error: ValidationErrors.Projection[$index].format}">
								<input type="hidden" name="data[Projection][{{$index}}][format]" data-ng-model="projection.format" value="{{ projection.format }}"/>
								<select
									value="{{ projection.format }}"
									data-ng-model="projection.format"
									data-ng-options = "format for format in formats">
									<option value="">[:Select_format:]</option>
								</select>
							</div>
						</td>
						<td>
							<div class="input select required " ng-class="{error: ValidationErrors.Projection[$index].lang}">
								<input type="hidden" name="data[Projection][{{$index}}][lang]" data-ng-model="projection.lang" value="{{ projection.lang }}"/>
								<select

									value="{{ projection.lang }}"
									data-ng-model="projection.lang"
									data-ng-options = "lang for lang in langs">
									<option value="">[:Select_lang:]</option>
								</select>
							</div>
						</td>
						<td>
							<button type="button" class="btn_danger" ng-click="del($index)"><i class="icon-remove"></i></button>
						</td>
					</tr>

					<tr class="noRecords">
						<td colspan="4">
							<i class="icon-list-ul icon"></i>
							<div>[:System.no_projections_yet:]</div>
						</td>
					</tr>
				</tbody>
			</table>
	</div>
	<hr />
	<button type="button" class="btn_info" ng-click="add()"><i class="icon-plus"></i>[:add_projection:]</button>

	<div class="deletes">
		<input type="hidden" data-ng-repeat="delete in deletes.Projection" name="data[deletes][Projection][]" value="{{delete}}" />
	</div>

</fieldset>

<?php
echo $this->Html->scriptBlock(
	"var Projections = ".$this->Javascript->object(isset($this->data['Projection'])? $this->data['Projection'] : array()).";".
	"var ValidationErrors = ".$this->Javascript->object($this->validationErrors).";"
);
?>