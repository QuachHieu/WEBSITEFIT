Date.prototype.ddmmyyyy = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [(dd > 9 ? '' : '0') + dd,
    (mm > 9 ? '' : '0') + mm,
    this.getFullYear()
    ].join('/');
};
$(document).ready(() => {
    //GetAllData
    let securityToken = $('[name=__RequestVerificationToken]').val();
    $("#tblresult").DataTable({
        "ajax": {
            "url": oAppPath + "m/document/getdocuments",
            "type": "POST",
            "data": (d) => {
                return $.extend({}, d, {
                    "__s": $('#__s').val(),
                    "__a": $('#__a').val(),
                    "__id": $('#__id').val(),
                    "__ed": $('#__ed').val(),
                    "__f": $('#__f').val(),
                    "__o": $('#__o').val(),
                    "__t": $('#__t').val(),
                    "__RequestVerificationToken": securityToken
                });
            },
            dataSrc: (json) => {
                json.data.forEach(element => {
                    element.idate = new Date(parseInt(element.IssuedDate.substr(6))).ddmmyyyy();
                    element.detail = `<a href="${oAppPath}vbpl/detail/${element.ID}">${element.Abstract}</a>`
                    element.method = `<a href="${element.FileUrl}"><i class="fa fa-download"></i></a>`;

                });
                return json.data;
            },
            complete: () => {
                //GAT_widgetMix();
            }
        },
        ordering:false,
        "oLanguage": {
            "sLengthMenu": "Số bản ghi hiển thị trên 1 trang _MENU_ ",
            "sInfo": "Hiển thị từ _START_ đến _END_ của _TOTAL_ bản ghi",
            "sSearch": "Tìm kiếm:",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            }
        },
        aoColumns: [
            { mData: 'ID' },
            { mData: 'Sign' },
            { mData: 'idate' },
            { mData: 'detail' },
            { mData: 'StatusName' },
            { mData: 'method' }
        ],
        bAutoWidth: false,
        fnPreDrawCallback: () => {
            // Initialize the responsive datatables helper once.

        },
        fnRowCallback: (nRow, aData, iDisplayIndex) => {
            $("td:first", nRow).html(iDisplayIndex + 1);
            return nRow;
        },
        fnDrawCallback: (oSettings) => {

        }
    });


});
