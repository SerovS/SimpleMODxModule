$j(document).ready(function() {
    $j('.zebra tr:even').addClass('wt');

    $j('.del').click(function() {
        if (confirm("Уверены, что хотите удалить?")) {
            return true;
        }
        else {
            return false;
        }
    });
});