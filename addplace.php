<?php

include "init.php";
?>

<div class="container">
    <div class="addplace">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <h2 class="title"><?php echo $lang["addplace"] ?></h2>
            </div>
            <form method="POST" action="<?php $_SERVER['PHP_SELF']?>" class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["name"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="name" class="form-control" placeholder="<?php echo $lang["namequote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["description"] ?> <b style="color: red">*</b></label>
                            <textarea name="description" placeholder="<?php echo $lang["descriptionquote"] ?>"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["budget"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="budget" class="form-control" placeholder="<?php echo $lang["budgetquote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["plan"] ?> <b style="color: red">*</b></label>
                            <textarea name="plan" placeholder="<?php echo $lang["planquote"] ?>"></textarea>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["address"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="address" class="form-control" placeholder="<?php echo $lang["addressquote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["city"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="city" class="form-control" placeholder="<?php echo $lang["cityquote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["province"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="province" class="form-control" placeholder="<?php echo $lang["provincequote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label><?php echo $lang["country"] ?> <b style="color: red">*</b></label>
                            <input type="text" name="country" class="form-control" placeholder="<?php echo $lang["countryquote"] ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <select name="type">
                            <option value="default"><?php echo $lang["placetype"] ?></option>
                            <option value="<?php echo $lang["cafe"] ?>"><?php echo $lang["cafe"] ?></option>
                            <option value="<?php echo $lang["restaurant"] ?>"><?php echo $lang["restaurant"] ?></option>
                            <option value="<?php echo $lang["club"] ?>"><?php echo $lang["club"] ?></option>
                        </select>
                    </div>

                    <div class="col-lg-2 offset-lg-10">
                        <input type="submit" value="<?php echo $lang["finish"] ?>" class="btn btn-primary">
                    </div>

                    <div class="col-lg-12" style="height: 30px;"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name        = $_POST["name"];
    $description = $_POST["description"];
    $budget      = $_POST["budget"];
    $plan        = $_POST["plan"];
    $type        = $_POST["type"];
    addplace($name, $description, $budget, $plan, $type);
}

?>
<?php
include "inc/footer.php";