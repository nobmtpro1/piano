var tableTitle = document.querySelectorAll(
  ".my-widget-table-course .title .box"
);
var tables = document.querySelectorAll(".my-widget-table-course .table");

tableTitle?.forEach((e) => {
  e?.addEventListener("click", function (x) {
    tableTitle?.forEach((e) => {
      e?.classList?.remove("active");
    });
    var tableActive = null;
    tables?.forEach((table) => {
      table?.classList?.remove("active");
      if (
        table?.getAttribute("data-tableid") == e?.getAttribute("data-tableid")
      ) {
        tableActive = table;
      }
    });
    tableActive?.classList?.add("active");
    e?.classList?.add("active");
  });
});
