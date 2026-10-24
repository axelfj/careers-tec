# Sistema de carreras del Tecnológico de Costa Rica


## Dependencias de interfaz

Las páginas PHP cargan versiones fijadas de Bootstrap, jQuery, Chart.js y DataTables desde CDN; package.json registra esas mismas versiones para auditarlas con `npm install --ignore-scripts` y `npm audit`. Los CSS y JS propios ya están compilados en css/ y js/. Se retiró la configuración heredada de Gulp porque los fuentes SCSS y el directorio vendor que requería no existen en este repositorio. La aplicación PHP no requiere Node.js en producción.
