<?php
Auth::routes(['register' => false]);

Route::get('/',function (){    
    if (!Auth::check() && !Request::is('login')) {
        return Redirect::route('login');
    }else{
        return Redirect::route('dashboard.index');
    }
}) ;
Route::get('/logout',['uses'=>'Auth\LoginController@logout','as'=>'logout']);
Route::group (['prefix'=>'admin','middleware'=>['disablepreventback','web', 'auth']],function() {     
    Route::get('/',['uses'=>'DashboardController@index','as'=>'dashboard.index']);          

    //masters - data kelompok urusan 
    Route::resource('/dmaster/kelompokurusan','DMaster\KelompokUrusanController',['parameters'=>['kelompokurusan'=>'id']]);           

    //setting - permissions    
    Route::resource('/setting/permissions','Setting\PermissionsController',[
                                                                            'parameters'=>['permissions'=>'id'],
                                                                            'only'=>['index','show','create','store','destroy']
                                                                        ]);               
    
    Route::get('/setting/permissions/paginate/{id}',['uses'=>'Setting\PermissionsController@paginate','as'=>'permissions.paginate']);    
    Route::post('/setting/permissions/search',['uses'=>'Setting\PermissionsController@search','as'=>'permissions.search']);          
    Route::post('/setting/permissions/changenumberrecordperpage',['uses'=>'Setting\PermissionsController@changenumberrecordperpage','as'=>'permissions.changenumberrecordperpage']);  
    Route::post('/setting/permissions/orderby',['uses'=>'Setting\PermissionsController@orderby','as'=>'permissions.orderby']);  
    
    //setting - roles
    Route::resource('/setting/roles','Setting\RolesController',['parameters'=>['roles'=>'id']]);           
    Route::get('/setting/roles/paginate/{id}',['uses'=>'Setting\RolesController@paginate','as'=>'roles.paginate']);    
    Route::post('/setting/roles/changenumberrecordperpage',['uses'=>'Setting\RolesController@changenumberrecordperpage','as'=>'roles.changenumberrecordperpage']);  
    Route::post('/setting/roles/orderby',['uses'=>'Setting\RolesController@orderby','as'=>'roles.orderby']);  
    Route::post('/setting/roles/storerolepermission', ['uses'=>'Setting\RolesController@storerolepermission','as'=>'roles.storerolepermission']);
    
    //setting - users
    Route::resource('/setting/users','Setting\UsersController',['parameters'=>['users'=>'id']]);           
    Route::get('/setting/users/paginate/{id}',['uses'=>'Setting\UsersController@paginate','as'=>'users.paginate']);
    Route::post('/setting/users/changenumberrecordperpage',['uses'=>'Setting\UsersController@changenumberrecordperpage','as'=>'users.changenumberrecordperpage']);  
    Route::post('/setting/users/orderby',['uses'=>'Setting\UsersController@orderby','as'=>'users.orderby']); 
    Route::post('/setting/users/search',['uses'=>'Setting\UsersController@search','as'=>'users.search']);    
    Route::post('/setting/users/filter',['uses'=>'Setting\UsersController@filter','as'=>'users.filter']);    
    Route::post('/setting/users/storeuserpermission', ['uses'=>'Setting\UsersController@storeuserpermission','as'=>'users.storeuserpermission']);

});