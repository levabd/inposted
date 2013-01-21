<h3>Preferences</h3>
<div ng-show="alerts.length">
    <div class="alert alert-{{a.type}}" ng-repeat="a in alerts" ng-hide="a.hide">
        <button type="button" class="close" ng-click="a.hide=true">×</button>
        {{a.msg}}
    </div>
</div>
<div>
    <form name="profile" novalidate>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Default spin dimensions</th>
                <th width="20">Enabled</th>
                <th width="80">Width (px)</th>
                <th width="20"></th>
                <th width="80">Height (px)</th>
                <th>JPEG quality (%)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Main 360 image</td>
                <td></td>
                <td>
                    <input type="number" class="input-mini" min=0 step="10"
                           ng-model="settings.small.width" ng-disabled="_busy">
                </td>
                <td>or</td>
                <td>
                    <input type="number" class="input-mini" min="0" step="10"
                           ng-model="settings.small.height" ng-disabled="_busy">
                </td>
                <td>
                    <input name="s" type="number" class="input-mini" min=1 max=100
                           ui-tooltip="Range 1 - 100"
                           ng-model="settings.small.compression" ng-disabled="_busy">
                </td>
            </tr>
            <tr>
                <td>
                    Zoomed 360 image
                </td>
                <td>
                    <input name="z_enabled" type="checkbox"
                           ui-tooltip="Enable zoom feature of Magic 360"
                           ng-model="settings.zoom.enabled" ng-disabled="_busy">
                </td>
                <td>
                    <input type="number" class="input-mini" min=0 step="10"
                           ng-model="settings.zoom.width" ng-disabled="_busy || !settings.zoom.enabled">
                </td>
                <td>or</td>
                <td>
                    <input type="number" class="input-mini" min="0" step="10"
                           ng-model="settings.zoom.height" ng-disabled="_busy || !settings.zoom.enabled">
                </td>
                <td>
                    <input name="z" type="number" class="input-mini" min=1 max=100
                           ui-tooltip="Range 1 - 100"
                           ng-model="settings.zoom.compression" ng-disabled="_busy || !settings.zoom.enabled">
                </td>
            </tr>
            <tr>
                <td>
                    Full-screen 360 image
                </td>
                <td>
                    <input name="f_enabled" type="checkbox"
                           ui-tooltip="Enable full-screen feature of Magic 360"
                           ng-model="settings.fullscreen.enabled" ng-disabled="_busy">
                </td>
                <td>
                    <input type="number" class="input-mini" min="0" step="10"
                           ng-model="settings.fullscreen.width" ng-disabled="_busy || !settings.fullscreen.enabled">
                </td>
                <td>or</td>
                <td>
                    <input type="number" class="input-mini" min="0" step="10"
                           ng-model="settings.fullscreen.height" ng-disabled="_busy || !settings.fullscreen.enabled">
                </td>
                <td>
                    <input name="f" type="number" class="input-mini" min=1 max=100
                           ui-tooltip="Range 1 - 100"
                           ng-model="settings.fullscreen.compression" ng-disabled="_busy || !settings.fullscreen.enabled">
                </td>
            </tr>
            </tbody>
            <thead>
            <th></th>
            <th colspan="5">
                <button class="btn btn-primary" ng-click="update()"
                        ng-disabled="_busy || profile.$pristine || profile.$invalid">Update
                </button>
            </th>
            </thead>
        </table>
    </form>
</div>