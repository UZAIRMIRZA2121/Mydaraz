// "use strict";

let productListPageBackup = $('.products-search-data-backup');
let productListPageData = {
    page: productListPageBackup.data('page'),
    id: productListPageBackup.data('id'),
    name: productListPageBackup.data('name'),
    brand_id: productListPageBackup.data('brand'),
    category_id: productListPageBackup.data('category'),
    data_from: productListPageBackup.data('from'),
    min_price: productListPageBackup.data('min-price'),
    max_price: productListPageBackup.data('max-price'),
    sort_by: productListPageBackup.data('sort_by'),
    product_type: productListPageBackup.data('product-type'),
    vendor_id: productListPageBackup.data('vendor-id'),
    author_id: productListPageBackup.data('author-id'),
    publishing_house_id: productListPageBackup.data('publishing-house-id'),
    search_category_value: productListPageBackup.data('search-category-value'),
};

// function getProductListFilterRender() {
//     const baseUrl = productListPageBackup.data('url');
//     const queryParams = $.param(productListPageData);
//     const newUrl = baseUrl + '?' + queryParams;
//     history.pushState(null, null, newUrl);
//     $.get({
//         url: productListPageBackup.data('url'),
//         data: productListPageData,
//         dataType: 'json',
//         beforeSend: function () {
//             $("#loading").addClass("d-grid");
//         },
//         success: function (response) {
//             $("#ajax-products-view").html(response.view);
//         },
//         complete: function () {
//             $("#loading").removeClass("d-grid");
//         },
//     });
// }

// $('.product-list-filter-on-sort-by').on('click', function () {
//     productListPageData.sort_by = $(this).data('value');
//     $(".product-view-sort-by button").html($(this).text());
//     getProductListFilterRender();
//     $(".product-list-filter-on-sort-by").removeClass("selected");
//     $(this).addClass("selected");
// })

// $('.filter-on-product-filter-change').on('click', function () {
//     productListPageData.data_from = $(this).data('value');
//     $(".filter-on-product-filter-button").html($(this).text());
//     getProductListFilterRender();
//     $(".filter-on-product-filter-change").removeClass("selected");
//     $(this).addClass("selected");
// })

$('.filter-on-product-type-change').on('click', function () {
    productListPageData.product_type = $(this).data('value');
    $(".filter-on-product-type-button").html($(this).text());

    if ($(this).data('value')?.toString() === 'digital') {
        $('.product-type-physical-checkbox').prop('checked', false);
        $('#product_type_digital').prop('checked', true);
    } else if ($(this).data('value')?.toString() === 'physical') {
        $('.product-type-digital-checkbox').prop('checked', false);
        $('#product_type_physical').prop('checked', true);
    } else {
        $('#product_type_all').prop('checked', true);
    }

    listPageProductTypeCheck();

    try {
        inputTypeNumberClick(1);
        fashion_products_list_form_common();
    } catch (error) {

    }
    // getProductListFilterRender();
});

function listPageProductTypeCheck() {
    if (productListPageData?.product_type?.toString() === 'digital') {
        $('.product-type-digital-section').show();
        $('.product-type-physical-section').hide();
    } else if (productListPageData?.product_type?.toString() === 'physical') {
        $('.product-type-digital-section').hide();
        $('.product-type-physical-section').show();
    } else {
        $('.product-type-physical-section').show();
        $('.product-type-digital-section').show();
    }
}
listPageProductTypeCheck();

// $('#min_price').on('change', function (){
//     productListPageData.min_price = $(this).val();
//     getProductListFilterRender();
// })

// $('#max_price').on('change', function (){
//     productListPageData.max_price = $(this).val();
//     getProductListFilterRender();
// })

// $('.action-search-products-by-price').on('click', function (){
//     getProductListFilterRender();
// })

