{include common/header@ebcms/installer}
<form action="{:$router->build('/ebcms/installer/index', ['step'=>4])}" method="POST">
    <div class="row">
        <div class="col-md-3">
            {include common/nav@ebcms/installer}
        </div>
        <div class="col-md-9">
            <div class="overflow-auto p-3" style="height: 400px;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">数据库地址：</label>
                        <input type="text" class="form-control" name="database_server" value="127.0.0.1" required>
                        <small class="form-text">通常是127.0.0.1</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">数据库端口：</label>
                        <input type="text" class="form-control" name="database_port" value="3306" required>
                        <small class="form-text">通常是3306</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">数据库帐号：</label>
                        <input type="text" class="form-control" name="database_username" value="root" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">数据库密码：</label>
                        <input type="text" class="form-control" name="database_password" value="root">
                    </div>
                    <div class="col-12">
                        <label class="form-label">数据库名称：</label>
                        <input type="text" class="form-control" name="database_name" value="" required>
                        <small class="form-text">本安装程序不会创建数据库，请先手动创建（mysql, utf8mb4）~</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">是否安装演示数据：</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="demo" value="0" id="demo0">
                            <label class="form-check-label" for="demo0">
                                不安装
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="demo" value="1" id="demo1" checked>
                            <label class="form-check-label" for="demo1">
                                安装
                            </label>
                        </div>
                        <small class="form-text">演示数据请勿用于正式项目~</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-end pt-4 border-top">
        <a class="btn btn-light" href="{:$router->build('/ebcms/installer/index', ['step'=>2])}" role="button">上一步</a>
        <button type="submit" class="btn btn-primary ms-2">安装</button>
    </div>
</form>
{include common/footer@ebcms/installer}