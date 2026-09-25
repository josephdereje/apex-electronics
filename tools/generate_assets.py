#!/usr/bin/env python3
"""Generate logo, WeChat QR placeholder, and product images."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont, ImageFilter
import math

ROOT = Path(__file__).resolve().parents[1]
IMG = ROOT / "assets" / "img"
PROD = IMG / "products"
IMG.mkdir(parents=True, exist_ok=True)
PROD.mkdir(parents=True, exist_ok=True)

ORANGE = (255, 107, 53)
DARK_ORANGE = (232, 84, 31)
NEAR_BLACK = (27, 27, 27)
CHARCOAL = (35, 35, 35)
WHITE = (255, 255, 255)
GRAY = (107, 114, 128)


def font(size, bold=False):
    candidates = [
        "/System/Library/Fonts/Supplemental/Arial Bold.ttf" if bold else "/System/Library/Fonts/Supplemental/Arial.ttf",
        "/Library/Fonts/Arial.ttf",
        "/System/Library/Fonts/Helvetica.ttc",
    ]
    for path in candidates:
        try:
            return ImageFont.truetype(path, size)
        except OSError:
            continue
    return ImageFont.load_default()


def rounded_rect(draw, box, radius, fill):
    draw.rounded_rectangle(box, radius=radius, fill=fill)


def make_logo():
    size = 512
    im = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    d = ImageDraw.Draw(im)
    rounded_rect(d, (24, 24, 488, 488), 96, ORANGE)
    rounded_rect(d, (64, 64, 448, 448), 80, NEAR_BLACK)
    f = font(118, bold=True)
    text = "APEX"
    bbox = d.textbbox((0, 0), text, font=f)
    tw, th = bbox[2] - bbox[0], bbox[3] - bbox[1]
    d.text(((size - tw) / 2, (size - th) / 2 - 8), text, font=f, fill=ORANGE)
    im.save(IMG / "logo.png")
    im.resize((160, 160), Image.Resampling.LANCZOS).save(IMG / "logo-mark.png")


def make_wordmark():
    im = Image.new("RGBA", (720, 160), (0, 0, 0, 0))
    d = ImageDraw.Draw(im)
    rounded_rect(d, (8, 20, 140, 140), 28, ORANGE)
    f = font(42, bold=True)
    d.text((28, 52), "A", font=f, fill=WHITE)
    title = font(54, bold=True)
    d.text((168, 28), "APEX", font=title, fill=NEAR_BLACK)
    sub = font(20)
    d.text((170, 94), "IMPORT & EXPORT", font=sub, fill=GRAY)
    im.save(IMG / "logo-wordmark.png")


def make_qr():
    size = 420
    im = Image.new("RGB", (size, size), WHITE)
    d = ImageDraw.Draw(im)
    cell = 14
    seed = 13640666344
    for y in range(4, size - 4, cell):
        for x in range(4, size - 4, cell):
            seed = (seed * 1103515245 + 12345) & 0x7FFFFFFF
            if seed % 3 == 0:
                d.rectangle((x, y, x + cell - 2, y + cell - 2), fill=NEAR_BLACK)
    for box in [(20, 20, 110, 110), (size - 110, 20, size - 20, 110), (20, size - 110, 110, size - 20)]:
        d.rectangle(box, fill=WHITE)
        d.rectangle((box[0] + 8, box[1] + 8, box[2] - 8, box[3] - 8), outline=NEAR_BLACK, width=10)
        d.rectangle((box[0] + 28, box[1] + 28, box[2] - 28, box[3] - 28), fill=NEAR_BLACK)
    d.rectangle((0, 0, size - 1, size - 1), outline=ORANGE, width=8)
    im.save(IMG / "wechat-qr.png")


def radial(size, inner, outer):
    im = Image.new("RGB", (size, size), outer)
    px = im.load()
    cx = cy = size / 2
    for y in range(size):
        for x in range(size):
            t = min(1.0, math.hypot(x - cx, y - cy) / (size * 0.72))
            px[x, y] = tuple(int(inner[i] * (1 - t) + outer[i] * t) for i in range(3))
    return im


def make_product(filename, title, subtitle, accent):
    w, h = 1200, 900
    bg = radial(max(w, h), accent, NEAR_BLACK).crop((0, 0, w, h)).filter(ImageFilter.GaussianBlur(1))
    d = ImageDraw.Draw(bg, "RGBA")
    d.ellipse((720, -180, 1380, 480), fill=(*ORANGE, 40))
    d.ellipse((-220, 520, 420, 1160), fill=(*DARK_ORANGE, 36))
    d.rounded_rectangle((70, 70, w - 70, h - 70), 36, outline=(*WHITE, 40), width=2)
    d.text((110, 620), subtitle.upper(), font=font(28, bold=True), fill=ORANGE)
    d.text((110, 670), title, font=font(62, bold=True), fill=WHITE)
    d.text((110, 760), "OEM / ODM  ·  Private label", font=font(26), fill=(209, 213, 219))
    bg.convert("RGB").save(PROD / filename, quality=92)


def make_hero():
    w, h = 1600, 1100
    im = radial(max(w, h), (46, 28, 20), NEAR_BLACK).crop((0, 0, w, h))
    d = ImageDraw.Draw(im, "RGBA")
    d.ellipse((900, -200, 1800, 700), fill=(*ORANGE, 50))
    d.ellipse((-300, 600, 600, 1500), fill=(*DARK_ORANGE, 40))
    im.convert("RGB").save(IMG / "hero-glow.png", quality=90)


def main():
    make_logo()
    make_wordmark()
    make_qr()
    make_hero()
    specs = [
        ("glass-privacy.jpg", "Privacy Glass 9H", "Tempered glass", (48, 32, 24)),
        ("glass-clear.jpg", "HD Clear 0.33mm", "Tempered glass", (36, 28, 22)),
        ("lcd-aaa.jpg", "AAA+ LCD Assembly", "Display screens", (28, 30, 40)),
        ("lcd-oled.jpg", "OLED Incell Panel", "Display screens", (24, 26, 38)),
        ("charger-gan.jpg", "65W GaN Charger", "Chargers", (40, 26, 20)),
        ("charger-pd.jpg", "30W PD Fast Charger", "Chargers", (42, 30, 22)),
        ("cable-braided.jpg", "Braided USB-C Cable", "Cables", (32, 34, 28)),
        ("cable-lightning.jpg", "Lightning 5A Cable", "Cables", (30, 32, 26)),
        ("audio-tws.jpg", "TWS ENC Earbuds", "Bluetooth audio", (26, 24, 36)),
        ("audio-overear.jpg", "OEM Over-Ear Headset", "Bluetooth audio", (28, 22, 32)),
        ("custom-odm.jpg", "Custom ODM Project", "Private label", (38, 24, 20)),
        ("glass-full.jpg", "Full Coverage Glass", "Tempered glass", (44, 30, 22)),
    ]
    for item in specs:
        make_product(*item)
    print(f"Generated assets in {IMG}")


if __name__ == "__main__":
    main()
