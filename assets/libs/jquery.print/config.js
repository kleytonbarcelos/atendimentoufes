function PrintElement(element)
{
    $(element).find('.btn').hide();
    setTimeout(function()
    {
        $(element).print();
        setTimeout(function()
        {
            $(element).find('.btn').show();
        }, 2000);
    }, 1000);
}