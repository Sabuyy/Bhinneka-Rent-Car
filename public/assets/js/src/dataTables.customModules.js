window.dtHelper = { };

/* Helper to transform datatable server side params to compatible with Pagination_library.php */
window.dtHelper.dataTransform = function(data) {
  const order = data.order.reduce(function(acc, item) {
    const columnName = data.columns[item.column].data;
    acc[columnName] = item.dir;
    return acc;
  }, { });

  return {
    draw: data.draw,
    start: data.start,
    length: data.length,
    term: data.search.value,
    order: order,
  };
}

/* Predefined datatable server side configuration */
window.dtHelper.serverSideConfig = {
  serverSide: true,
  processing: true,
  responsive: true,
  select: 'single',
  aaSorting: [ ],
  ajax: {
    data: dtHelper.dataTransform,
  },
  columnDefs: [
    {searchable: false, targets: [0]}
  ],
};

/* Row numbering function */
window.dtHelper.incrementNumber = function (dt, i, r, meta) {
  return meta.row + meta.settings._iDisplayStart + 1;
};

/* Plugin to enable navigate row(s) using arrow key */
$.fn.dataTable.Api.register('arrowNavigation()', function (options) {
  options = options || { };
  const table = this.table();

  function selectedRow () {
    return table.rows({ selected: true })[0];
  };

  function selectPreviousRow (selected) {
    const row = table.row(selected[0] - 1);
    if(row[0].length) {
      row.select();
    }
    else {
      navigatePreviousPage(table.page.len() - 1);
    }
  }

  function selectNextRow (selected) {
    const row = table.row(selected[0] + 1);
    if(row[0].length) {
      row.select();
    }
    else {
      navigateNextPage(0);
    }
  }

  function safeNavigate (direction, selected) {
    const fn = function() {
      table.row(selected).select();
      table.off('draw', fn);
      table.node().addEventListener('keydown', handler);
    }
    table.on('draw', fn);
    table.page(direction).draw('page');
    table.node().removeEventListener('keydown', handler);
  }

  function navigatePreviousPage (selected) {
    if(table.page() > 0) {
      safeNavigate('previous', selected);
    }
  }

  function navigateNextPage (selected) {
    if(table.page() < table.page.info().pages - 1) {
      safeNavigate('next', selected);
    }
  }

  function handler (event) {
    const selected = selectedRow();
    if (selected) {
      switch (event.key) {
        case 'ArrowUp':
          if(typeof options.arrowUp === 'function') {
            options.arrowUp(selectPreviousRow, selected);
          }
          else {
            selectPreviousRow(selected);
          }
          break;
        case 'ArrowDown':
          if(typeof options.arrowDown === 'function') {
            options.arrowDown(selectNextRow, selected);
          }
          else {
            selectNextRow(selected);
          }
          break;
        case 'ArrowLeft':
          if(typeof options.arrowLeft === 'function') {
            options.arrowLeft(navigatePreviousPage, selected);
          }
          else {
            navigatePreviousPage(selected);
          }
          break;
        case 'ArrowRight':
          if(typeof options.arrowRight === 'function') {
            options.arrowRight(navigateNextPage, selected);
          }
          else {
            navigateNextPage(selected);
          }
          break;
      }
    }
  }

  table.node().addEventListener('keydown', handler);

  return this;
});

/* Redraw, but keep selected row and visited page */
$.fn.dataTable.Api.register('redrawAndKeep()', function(options) {
  const table = this;
  const currentPage = table.page();
  const currentRow = table.rows({ selected: true });
  table.page(currentPage).draw('page');
  const fn = function() {
    table.row(currentRow).select();
    table.off('draw', fn);
  }
  table.on('draw', fn);
});

$.fn.dataTable.Api.register('checkable()', function(options) {
  const node = $(this.table().node());
  const dt = this;
  node.find('input[type=checkbox]').prop('checked', false);
  // console.log("okeoke");
  // window.nodeGlobal = node;
  node.parents('.dataTables_scroll').first().find('.dataTables_scrollHead th input[type=checkbox]').eq(0).on('click', function() {
    console.log("oke");
    let thChecked = this.checked;
    $('.table-checkable').find('tbody > tr').each(function() {
      var row = this;
      if(thChecked) dt.row(row).select();
      else dt.row(row).deselect();
    });
  });
  
  this.on('click', 'td input[type=checkbox]' , function(){
    let table = $(this).parents('table').DataTable();
    let row = $(this).closest('tr').get(0);
    if(this.checked) table.row(row).deselect();
    else table.row(row).select();
  })
  .on('select', function (e, dt, type, index) {
    if(type == 'row') {
      let table = $(this).DataTable();
      $(table.row(index).node()).find('input[type=checkbox]').prop('checked', true);
    }
  })
  .on('deselect', function (e, dt, type, index) {
    if (type === 'row') {
      let table = $(this).DataTable();
      $(table.row(index).node()).find('input[type=checkbox]').prop('checked', false);
    }
  });

  return dt;
});

/* Get selected row data */
$.fn.dataTable.Api.register('selectedRow()', function(options) {
  return this.rows({ selected: true }).data()[0];
});

/* Get selected rows data */
$.fn.dataTable.Api.register('selectedRows()', function(options) {
  return this.rows({ selected: true }).data();
});