import './bootstrap';
import QRCode from 'qrcode';

window.renderShareQr = function (canvas, text) {
    QRCode.toCanvas(canvas, text, { width: 180, margin: 1 });
};
