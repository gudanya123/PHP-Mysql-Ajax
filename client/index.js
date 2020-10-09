var total = 0;

var currentPage = 1;

var pageSize = 1;


var isInit = false;

function Init(page,num) {
    if(!isInit){
        isInit = true;

        currentPage = page;
        total = num;

        changePage(currentPage);
    }

}

function setCurrentPage(page) {
    if(page > total || page < 1){
        return false;
    }
    currentPage = parseInt(page);

    return true;
    // console.log(currentPage);
}


function changePage(page) {
    let pageArr = [],light;

    if(page > total || page < 1){
        return false;
    }

    page = parseInt(page);
    $('#goInput').val(currentPage);

    if(page > total - 5){
        pageArr = [1,'...',total-6,total-5,total-4,total-3,total-2,total-1,total];

        light = 8 - (total - page);
    }else if(page < 6){
        pageArr = [1,2,3,4,5,6,7,'...',total];
        light = page - 1;
    }else{
        pageArr = [1,'...',page-2,page-1,page,page+1,page+2,'...',total];
        light = 4;
    }

    // console.log(pageArr);

    renderPage(pageArr,light);

    return true;

}

function renderPage(pageArr,light){
    for(let i=0;i<pageArr.length;i++){
        $('.list-page').eq(i).text(pageArr[i]);

        if(pageArr[i] === '...'){
            $('.list-page').eq(i).css('border','none');
        }else if(i === 1 || i === pageArr.length - 2){
            $('.list-page').eq(i).css('border','1px solid #ccc');
        }
    }

    $('.list-page').css('borderColor','#ccc').css('color','#000').css('background-color','#fff');
    $('.list-page').eq(light).css('color','#fff').css('background-color','#555');
}


$('#list').on('click','li',function () {
    switch($(this).text()){
        case '...':{
            break;
        }
        case '上一页':{
            setCurrentPage(currentPage - 1) && getData();
            break;
        }
        case '下一页':{
            setCurrentPage(currentPage + 1) && getData();
            break;
        }
        default:{
            setCurrentPage($(this).text()) && getData();
            break;
        }
    }

})


function getData(){
    $.ajax({
        url: 'http://localhost/php/mysql/server/index.php',
        type: 'GET',
        data:{
            page_size: pageSize,
            page_index: currentPage
        },
        dataType: 'json',
        success: function(res){
            if(res.code === 0){
                let totalPage = res.data.totalPage;
                Init(currentPage,totalPage);

                changePage(currentPage);

                $('#data').html('');
                res.data.data.forEach(element => {
                    let el = `<tr>
                        <td>${element.id}</td>>
                        <td>${element.username}</td>
                        <td>${element.password}</td>
                    </tr>`;

                    $('#data').append(el);
                })
            }else{
                console.log("请求失败");
            }

        },
        fail: function(err){
            console.log("稍后重试");
        }
    })
}


// Init(20,100);
