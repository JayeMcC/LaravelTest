window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // Update the URL to point to the route that serves your local OpenAPI YAML file
  window.ui = SwaggerUIBundle({
    url: "/swagger/openapi.yml",  // Path to your OpenAPI YAML file served via Laravel
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  });

  //</editor-fold>
};
