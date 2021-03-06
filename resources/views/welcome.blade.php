<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start Verify Phone Verification - Account Security Quickstart</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js"></script>
    <script src="/app.js"></script>
    <style>
        .container {
            padding-top: 15%;
        }
    </style>
</head>
<body ng-app="accountSecurityQuickstart">

<div class="container" ng-controller="PhoneVerificationController">
    <div class="row centered-form">
        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Verify Phone Verification</h3>
                </div>
                <div class="panel-body" ng-show="view.start">


                        <input type="submit" value="Request Verification"
                               ng-click="startVerification()"
                               class="btn btn-info btn-block">
                </div>
                <div class="panel-body" ng-hide="view.start">
                    <form role="form">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="token"
                                           id="token"
                                           ng-model="setup.token"
                                           class="form-control"
                                           placeholder="Verification Token">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="submit" value="Verify Phone"
                                           ng-click="verifyToken()"
                                           class="btn btn-info btn-block">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>