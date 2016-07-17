/**
 * Item
 */
var item = {};

/**
 * List of all tables
 */
item.tables = [];


/**
 * Initalization
 */
item.ini = function(){
	for(i in item.tables){
		table = item.tables[i];
		
		item.updateIconSort(table,table.list.sortByField,table.list.sortByDirection);
		item.getListWithParams(table);

	};
};

/**
 * Add a table
 *
 * @param {object} table
 */
item.addTable = function(table){
	item.tables[table.name] = table;
};

/**
 * Get a table
 *
 * @param {string} name
 * @return{object}
 */
item.getTable = function(name){
	return item.tables[name];
};

/**
 * Update the list of all records
 *
 * @param {object} table
 */
item.getListWithParams = function(table){

	var container = $('[data-item-table-container='+table.name+']');

	// Show and pages
	var params = {}

	// Show
	params.show = table.list.show;

	// Page
	params.page = table.list.page

	// Sorting
	table.list.sortByDirection == 'asc' ? params.asc = table.list.sortByField : params.desc = table.list.sortByField;

	// Search
	params.search = table.search.data;

	// Send request
	item.getList(table,params);
};

/** 
 * Update show result
 */
$('[data-item-show]').on('change',function(){
	var table = item.getTable($(this).attr('data-item-table'));
	table.list.show = $(this).val();
	item.getListWithParams(table);
});

/** 
 * Search
 */
$('[data-item-search]').on('click',function(){
	var table = item.getTable($(this).attr('data-item-table'));

	var container = item.getContainerByTable(table);	
	var values = table.search.action(container.find('.table-row-search').first(9));

	table.search.data = values;

	item.getListWithParams(table);
});

item.addParamSearch = function(){

};

/**
 * Update the list of all records
 *
 * @param {object} table
 * @param {array} params
 */
item.getList = function(table,params = {}){
		
	//template.setBySource('spinner-table','item-row',{});

	http.get(table.list.url,params,function(response){
		var container = item.getContainerByTable(table);

		if(response.status == 'success'){
			var rows = '';

			data = response.data;
			results = data.results;
			$.map(results,function(row){
				row.table = table.name;
				rows += template.get(table.template.row,row);
			});

			template.setByHtml(rows,table.template.row);

			table.list.page = data.pagination.page;
			table.list.pages = data.pagination.pages;
			table.list.count = data.pagination.count;

			// Update
			container.find('[data-item-list-page]').html(data.pagination.page);
			container.find('[data-item-list-pages]').html(data.pagination.pages);
			container.find('[data-item-list-count]').html(data.pagination.count);
			container.find('[data-item-list-start]').html(data.pagination.from);
			container.find('[data-item-list-end]').html(data.pagination.to);

			item.updateListPagination(table);

		}

		if(response.status == 'error'){
			item.addAlert('alert-danger','alert-global',response);
		}

	});
};

item.getContainerByTable = function(table){
	return container = $('[data-item-table-container='+table.name+']');
};

item.updateListPagination = function(table){

	var container = item.getContainerByTable(table);

	if(table.list.page == 1)
		container.find('[data-item-list-prev]').addClass('disable');
	else
		container.find('[data-item-list-prev]').removeClass('disable');
	

	if(table.list.page == table.list.pages)
		container.find('[data-item-list-next]').addClass('disable');
	else
		container.find('[data-item-list-next]').removeClass('disable');
	
};

item.listPrev = function(table){
	if(table.list.page > 1){
		table.list.page--;
		item.getListWithParams(table);
	}

	item.updateListPagination(table);

};

item.listNext = function(table){
	if(table.list.page < table.list.pages){
		table.list.page++;
		item.getListWithParams(table);
	}

	item.updateListPagination(table);
};

/** 
 * Add a row
 */
item.add = function(table,values){

	http.post(table.add.url,values,function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-modal-add',data);
		}
	});
}

/** 
 * Edit a row
 */
item.edit = function(table,id,values){

	http.put(table.edit.url+"/"+id,values,function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-modal-edit',data);
		}
	});
}

/**
 * Remove a row
 *
 * @param {object} table
 * @param {string} id
 */
item.remove = function(table,id){

	http.delete(table.delete.url+"/"+id,{},function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-global',data);
		}
	});
};

/**
 * Copy a row
 *
 * @param {object} table
 * @param {string} id
 */
item.copy = function(table,id){

	http.post(table.copy.url+"/"+id,{},function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-global',data);
		}
	});
};

/**
 * Set event sort
 */
$('body').on('click','[data-item-sort-field]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var field = $(this).attr('data-item-sort-field');

	var direction = 'asc';

	if(table.list.sortByField == field){
		direction = table.list.sortByDirection; 
	}else{

	}

	direction = item.getOppositeSort(direction);

	table.list.sortByDirection = direction;
	table.list.sortByField = field;

	item.getListWithParams(table);

	item.updateIconSort(table,field,direction);

});

$('body').on('click','[data-item-list-prev]',function(){
	var table = item.getTable($(this).attr('data-item-table'));
	item.listPrev(table);
});

$('body').on('click','[data-item-list-next]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	item.listNext(table);
});

/**
 * Update icon sort
 */
item.updateIconSort = function(table,field,direction){
	var container = $('[data-item-table-container='+table.name+']');
	container.find('[data-item-sort-none]').removeClass('hide');
	container.find('[data-item-sort-asc]').addClass('hide');
	container.find('[data-item-sort-desc]').addClass('hide');

	var sort = container.find('[data-item-sort-field='+field+']');
	var sort_direction = direction == 'asc' ? '[data-item-sort-asc]' : '[data-item-sort-desc]';

	sort.find('[data-item-sort-none]').addClass('hide');
	sort.find(sort_direction).removeClass('hide');
};

item.getOppositeSort = function(sort){
	return sort == 'asc' ? 'desc' : 'asc';
};

/**
 * Set event add
 */
$('body').on('submit','[item-data-form-add]',function(e){

	e.preventDefault();

	var table = item.getTable(table = $(this).attr('data-item-table'));
	var values = table.add.action($(this));

	item.add(table,values);

});

/**
 * Set event remove
 */
$('body').on('click','[data-item-remove]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var id = $(this).attr('data-item-id');

	item.remove(table,id);
});

/**
 * Set event copy
 */
$('body').on('click','[data-item-copy]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var id = $(this).attr('data-item-id');

	item.copy(table,id);
});

/**
 * Set event edit
 */
$('body').on('submit','[item-data-form-edit]',function(e){

	e.preventDefault();

	var table = item.getTable($(this).attr('data-item-table'));
	var id = $(this).attr('data-item-id');
	var values = table.edit.action($(this));

	item.edit(table,id,values);

});

modal.addDataTo('modal-item-edit',function(container,data){
	var el = container.find('[item-data-form-edit]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	http.get(table.get.url+"/"+id,{filter:'edit'},function(data){
		
		table.edit.get(container,data.data.resource);

	});
});


modal.addDataTo('modal-item-get',function(container,data){
	var el = container.find('[item-data-form-get]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	http.get(table.get.url+"/"+id,{filter:'get'},function(data){
		table.get.get(container,data.data.resource);

	});
});

/**
 * Add Status
 *
 * @param {object} alert
 */
item.addAlert = function(type,destination,data){

	det = '';

	for(index in data.details){
		detail = data.details[index];
		det += template.get('alert-details',{
			message:index+": "+detail
		});
	}

	template.setBySource(type,destination,{
		message:data.message,
		details:det
	});
};

/**
 * Remove Status
 *
 * @param {object} alert
 */
item.removeAlert = function(alert){
	
};


/**
 * Data in modal delete
 */
modal.addDataTo('modal-item-delete',function(el,data){
	var del = el.find('[data-item-remove]');
	del.attr('data-item-table',data['data-modal-item-table']);
	del.attr('data-item-id',data['data-modal-item-id']);
});

/**
 * Data in modal add
 */
modal.addDataTo('modal-item-add',function(el,data){
	var el = el.find('[item-data-form-add]');
	el.attr('data-item-table',data['data-modal-item-table']);
});

/**
 * Initialize
 */
$(document).ready(function(){
	item.ini();
});


$('body').on('click','[data-item-select-all]',function(){
	var table = item.getTable($(this).attr('data-item-table'));

	var container = $('[data-item-table-container='+table.name+']');
	
	container.find('[data-item-select]').prop('checked', $(this).prop('checked'));

});


item.getSelectedRecords = function(container){
	var checkbox = container.find('[data-item-select]:checked');

	var ids = [];
	$.map(checkbox,function(value){
		ids.push($(value).attr('data-item-id'));
	});

	return ids;
}

/**
 * Set event delete multiple
 */
$('body').on('click','[data-item-multiple-delete]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var container = item.getContainerByTable(table);
	var ids = item.getSelectedRecords(container);
	console.log(ids);
	$.map(ids,function(id){
		item.remove(table,id);
	});
});