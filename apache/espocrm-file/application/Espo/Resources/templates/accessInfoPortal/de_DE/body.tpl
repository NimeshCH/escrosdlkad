<h3>Ihre NupiCRM Zugriffsinformation</h3>

<p>Benutzername: {{userName}}</p>
<p>{{#if password}}Passwort: {{password}}{{/if}}</p>

{{#each siteUrlList}}
<p><a href="{{./this}}">{{./this}}</a></p>
{{/each}}
