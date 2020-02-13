import html2canvas from "html2canvas";

console.log('Hello World!');
document.write(`<h1>Hello World!</h1>`);

html2canvas(document.body.querySelector('h1') as HTMLElement).then(function(canvas) {
  document.body.appendChild(canvas);
});