const Ziggy = {"url":"http:\/\/laravel.wwy2121.top","port":null,"defaults":{},"routes":{"telescope":{"uri":"telescope\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"},"parameters":["view"]},"home":{"uri":"\/","methods":["GET","HEAD"]},"admin.home":{"uri":"admin","methods":["GET","HEAD"]},"admin.login":{"uri":"admin\/login","methods":["GET","HEAD"]},"admin.":{"uri":"admin\/login","methods":["POST"]},"admin.logout":{"uri":"admin\/logout","methods":["POST"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
