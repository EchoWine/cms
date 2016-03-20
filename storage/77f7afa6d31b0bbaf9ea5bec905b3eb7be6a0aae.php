<div id='content'>

	<?php foreach(TemplateEngine::getInclude("/Item/admin/main.cat") as $k) include $k; ?>
	<?php foreach(TemplateEngine::getInclude("/Item/admin/response") as $k) include $k; ?>

	<form method='POST'>
		<input type='hidden' name='transaction'>

		<div class='content-header'>

			<?php $actionPage = 'list'; ?><?php foreach(TemplateEngine::getInclude("/Item/admin/main.title") as $k) include $k; ?>

			<?php if($item -> getAdd()){ ?>
				<a href='<?php echo $item -> getUrlPageAdd(); ?>' class='button a success'>
					<span class='fa fa-plus-circle'></span> <span class='button-label'>Add</span>
				</a>
			<?php } ?>
			
			<!--
			<a class='button a primary' href='data.php?p=data_sett'>
				<span class='fa fa-cogs'></span>
				Preferenze
			</a>


			<span class='button a primary' type='button'>
				<span class='fa fa-upload'></span>Importa
			</span>

			<span class='button a primary' type='button'>
				<span class='fa fa-download'></span> Esporta
			</span>

			<span class='b-button button a danger'>
				<span class='fa fa-trash'></span> Formatta
			</span>
			-->
		</div>
	</form>

	<?php foreach(TemplateEngine::getInclude("/Item/admin/main-list.pag") as $k) include $k; ?>

	<form method='POST' enctype='multipart/form-data'>

		<?php if($item -> getAdd() || $item -> getDelete() || $item -> getCopy()){ ?>
			<div class='data-multiple'>
			
				<select class='select n' name='<?php echo $item -> getDataName('action'); ?>' id='data-multiple-value' item-primary-value='0'>
					<option value=''>Select...</option>
					<!--
					<option value='export_xml_m'> Esporta XML </option>
					<option value='export_csv_m'> Esporta CSV </option>
					<option value='edit_multiple'> Modifica </option> 
					-->

					<?php if($item -> getCopy()){ ?>
						<option value='<?php echo $item -> getDataOption('action','copy_m'); ?>'> Copia </option>
					<?php } ?>

					<?php if($item -> getDelete()){ ?>
						<option value='<?php echo $item -> getDataOption('action','delete_m'); ?>' box-title='Conferma azione' box-desc='Sei sicuro di voler eliminare?'>	Elimina
						</option>
					<?php } ?>

					<?php if($item -> getEdit()){ ?>
						<option value='edit' item-action-multiple='edit' item-base-url='<?php echo $item -> getUrlPageEdit(); ?>' item-name-get='<?php echo $item -> getDataName('g_primary_m'); ?>'>
							Modifica
						</option>
					<?php } ?>
				</select>

				<button type='submit' class='button n' id='data-multiple-button' data-multiple-button confirm-target='data-multiple-value' confirm-event='click,submit'>Send</button>

				<!--
				<button type='submit' name='force_update' class='button lf'>
					<div class='button a info'> <span class='fa fa-terminal'></span>Forza aggiornamento </div>
				</button> 
				-->
			</div>
		<?php } ?>

		<div class='data-table' id='data'>
			<div class='row head'>
				<div class='check-row'>
					<input type='checkbox' class='check b d' id='check-all0' item-checkall='0'>
					<label class='noselect m-5' for='check-all0'><span></span></label>
				</div>
				
				<?php foreach((array)$item -> getFieldsList() as $field){ ?>
					
					<a href='<?php echo $item -> getUrlPageList($field -> name); ?>'> 
						<span class='title'><?php echo $field -> label; ?></span>
						
						<button class='button r'>

							<span class='fa fa-sort<?php switch($item -> getOrderField($field -> name)){ ?>
<?php case 'desc': ?>-desc<?php break; ?>
<?php case 'asc': ?>-asc<?php break; ?>
<?php default: ?> inactive<?php break; ?>
<?php } ?>'></span>
						</button>
						
					</a>

				<?php } ?>

				<div class='last action'> Azione </div>
			</div>
			
			<div class='row search' id='dataTable_searchBar'>
				<div class='check-row'></div>

				<?php foreach((array)$item -> getFieldsSearch() as $field){ ?>

					<div data-search-data='search_button_send'>
						<div item-table-column='<?php echo $field -> label; ?>' class='container-input-search'><?php $name = $field -> getFormNameSearch();$value = ''; ?><?php foreach(TemplateEngine::getInclude("/Item/".$field -> getPathInputData()."") as $k) include $k; ?></div> 
						<div class='container-key-search'><?php foreach((array)$item -> getSearched($field -> name) as $searched){ ?>
							<div class='container-input-search'>
								<?php $name = $field -> getFormNameSearch();$value = $field -> printInputValueSearch($searched); ?><?php foreach(TemplateEngine::getInclude("/Item/".$field -> getPathInputData()."") as $k) include $k; ?>
								<div class="container-delete-key"><button type="button" class="button a i danger"><span class="fa fa-trash"></span></button></div>
							</div>
						<?php } ?></div>
					</div>

				<?php } ?>

				<div class='last action'>
					<button type='submit' item-primary-value value='<?php echo $item -> getDataOption('action','search'); ?>' class='button'>
						<div class='button a i primary'>
							<span class='fa fa-search'></span>
						</div>
					</button>
					<a href='<?php echo $item -> getUrlPageList(); ?>' class='button a i warning' data-search-class=''>
							<span class='fa fa-eraser'></span>
					</a>
					<span class='button a i primary'>
						<span class='fa fa-link'></span>
					</span>
				</div>
			</div>

			<?php $i = 0;?>
			<?php foreach((array)$item -> results -> records as $record){ ?>
				<div class='row'>
					<div class='check-row'>
						<input type='checkbox' name="<?php echo $item -> getDataName('p_primary_m'); ?>[]" value='<?php echo $record[$item -> getFieldPrimary() -> column]; ?>' id='selectItem_0_<?php echo $i; ?>' class='check b d' item-check='0'><label class='noselect m-5' for='selectItem_0_<?php echo $i++; ?>'><span></span></label>
					</div>

					<?php foreach((array)$item -> getFieldsList() as $field){ ?>
						<div class='info string' item-table-column='<?php echo $field -> label; ?>'><div><?php echo $record[$field -> getColumnName()]; ?></div></div>
					<?php } ?>

					<div class='last action'>
						<!--
						<span class='button a i primary' data-button-action='1'>
							<span class='fa fa-download'></span>
						</span>
						-->

						<?php if($item -> getAdd()){ ?>
							<a data-button href='<?php echo $item -> getUrlPageAdd($record[$item -> getFieldPrimary() -> column]); ?>' class='button a i success'>
								<span class='fa fa-plus-square'></span>
							</a>
						<?php } ?>
						
						<?php if($item -> getCopy()){ ?>
						<button type='submit' class='button' name='<?php echo $item -> getDataName('action'); ?>' value='<?php echo $item -> getDataOption('action','copy_s'); ?>' item-primary-value ='<?php echo $record[$item -> getFieldPrimary() -> column]; ?>'>
							<span class='button a i success'>
								<span class='fa fa-copy'></span>
							</span>
						</button>
						<?php } ?>

						<?php if(true){ ?>
							<a data-button href='<?php echo $item -> getUrlPageView($record[$item -> getFieldPrimary() -> column]); ?>' class='button a i info'>
								<span class='fa fa-file-text'></span> 
							</a>
						<?php } ?>

						<?php if($item -> getEdit()){ ?>
						<a data-button href='<?php echo $item -> getUrlPageEdit($record[$item -> getFieldPrimary() -> column]); ?>' class='button a i warning'>
							<span class='fa fa-pencil'></span>
						</a>
						<?php } ?>

						<?php if($item -> getDelete()){ ?>
							<button type='submit' class='button' name='<?php echo $item -> getDataName('action'); ?>' value='<?php echo $item -> getDataOption('action','delete_s'); ?>' item-primary-value='<?php echo $record[$item -> getFieldPrimary() -> column]; ?>' confirm-target='this' confirm-event='click,submit' box-title='Confirm' box-desc='Are you sure?'>
								<span class='button a i danger'>
									<span class='fa fa-trash'></span>
								</span>
							</button>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<input type='hidden' name='transaction' value='205ec7bdccb067bebf0cc3f0335d57915e0e18a9'>

		<input type='hidden' item-primary name="<?php echo $item -> getDataName('p_primary'); ?>" value='0'>
		<input type='hidden' item-action name="<?php echo $item -> getDataName('action'); ?>" value=''>
	</form>

	<?php foreach(TemplateEngine::getInclude("/Item/admin/main-list.pag") as $k) include $k; ?>
	
</div>


<div id='box-container'>
	<div class='box-window' id='box-confirm'>
		<div class='box-content'>
			<div>
				<h1 box-data-title></h1>
				<button class='button a i danger' box-close>
					<span class='fa fa-close'></span>
				</button>
			</div>
			<div>
	
				<span box-data-message></span>

				<div class='box-confirm-button'>
					<button type='submit' class='button a danger' box-send>Yes</button>
					<button type='button' class='button a info' box-close>No</button>
				</div>

			</div>
		</div>
	</div>
</div>