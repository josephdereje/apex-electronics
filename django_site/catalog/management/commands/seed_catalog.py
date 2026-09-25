from pathlib import Path
from shutil import copyfile

from django.conf import settings
from django.contrib.auth import get_user_model
from django.core.management.base import BaseCommand

from catalog.models import Category, Product

CATEGORIES = [
    {
        "name": "Tempered Glass",
        "slug": "tempered-glass",
        "eyebrow": "01",
        "summary": "High-clarity tempered glass and protective-film options, including 360CC large-arc, 28° privacy glass, and SuperX flexible membrane.",
        "highlights": "\n".join(
            [
                "Large-arc, privacy and flexible membrane ranges",
                "HD clear and anti-peep privacy options",
                "Anti-static, anti-fingerprint surface treatments",
                "Full-coverage formats for current phone models",
                "Multiple retail packaging directions",
                "Logo, color, packaging and documentation OEM",
            ]
        ),
        "sort_order": 1,
    },
    {
        "name": "Bluetooth Headphones",
        "slug": "bluetooth-headphones",
        "eyebrow": "02",
        "summary": "True-wireless earbuds with retail packaging and multiple case formats: compact stem, ear-hook and lifestyle designs.",
        "highlights": "\n".join(
            [
                "Bluetooth 6.0 models across the supplied range",
                "Compact charging cases with display options",
                "Ear-hook, in-ear and short-stem form factors",
                "Multiple colorways for private-label programs",
                "Retail box artwork and specification panels",
                "Logo, color, packaging and documentation OEM",
            ]
        ),
        "sort_order": 2,
    },
    {
        "name": "Power Banks",
        "slug": "power-banks",
        "eyebrow": "03",
        "summary": "Portable chargers from 5,000 to 20,000 mAh with PD and QC fast charging, digital displays and multi-layer safety.",
        "highlights": "\n".join(
            [
                "5,000 / 10,000 / 20,000 mAh capacities",
                "PD and QC fast-charging models",
                "LED digital-display options",
                "Dual-port and multi-port output",
                "Slim, mini and magnetic formats",
                "CE / FCC / RoHS compliant designs",
            ]
        ),
        "sort_order": 3,
    },
    {
        "name": "Chargers & Cables",
        "slug": "chargers-cables",
        "eyebrow": "04",
        "summary": "GaN and standard wall chargers, car chargers, and braided or PVC cables in Lightning, USB-C and Micro USB formats.",
        "highlights": "\n".join(
            [
                "Wall, car and GaN chargers from 20W to 65W",
                "Lightning, USB-C and Micro USB cables",
                "Nylon-braided and PVC jacket options",
                "2.4A / 3A / 5A fast-charging support",
                "Custom lengths from 0.25 m to 3 m",
                "EU / UK / US / AU plug versions",
            ]
        ),
        "sort_order": 4,
    },
]

PRODUCTS = [
    {
        "category": "tempered-glass",
        "name": "360CC Large-Arc Glass",
        "sku": "APX-TG-360CC",
        "short_description": "Rinda 360CC ultra large-arc glass with ESD anti-static coating and retail-ready packaging.",
        "description": "High-clarity large-arc tempered glass for current flagship models. The supplied range includes promotional lifestyle imagery, water-resistance and cold-impact concepts, plus private-label packaging artwork.",
        "specifications": "360CC large-arc coverage\nESD anti-static treatment\nAnti-fingerprint oil coating\nFull-coverage for current models\nCustom logo and retail box",
        "moq": "2,000 pcs / model",
        "lead_time": "Samples 3–7 days",
        "image": "glass-360cc.jpg",
        "is_featured": True,
    },
    {
        "category": "tempered-glass",
        "name": "28° Privacy Glass",
        "sku": "APX-TG-PRIV28",
        "short_description": "28-degree anti-peep privacy glass with holographic retail packaging and standing display options.",
        "description": "Privacy-film packaging and product presentation from the APEX catalog, including front retail sets, product detail, dark-view examples and size comparison assets.",
        "specifications": "28° privacy filter\nHD visual clarity from the front\nRetail set packaging\nStanding display options\nPrivate-label artwork",
        "moq": "2,000 pcs / model",
        "lead_time": "Samples 3–7 days",
        "image": "glass-privacy.jpg",
        "is_featured": True,
    },
    {
        "category": "tempered-glass",
        "name": "SuperX Protective Membrane",
        "sku": "APX-TG-SUPERX",
        "short_description": "Flexible unbreakable membrane with full-screen coverage and multi-model free-cutting support.",
        "description": "SuperX HD-ESD flexible protection for buyers who need impact-resistant film, promotional concepts and model-updated coverage by project.",
        "specifications": "Flexible full-screen membrane\nAnti-bubble / anti-fingerprint\nMulti-model cutting support\nRetail display concepts\nOEM documentation",
        "moq": "2,000 pcs / model",
        "lead_time": "Mass production 15–30 days",
        "image": "glass-superx.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B68 Wireless Earbuds",
        "sku": "APX-BT-B68",
        "short_description": "Bluetooth 6.0 TWS with digital display case and black, champagne and pink colorways.",
        "description": "Catalog model B68 from Wireless Earbuds Range I. Compact case with display, multiple private-label colors and retail box artwork.",
        "specifications": "Model B68\nBluetooth 6.0\nCase display option\nBlack / champagne / pink\nABS / PC housing",
        "moq": "1,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "audio-b68.jpg",
        "is_featured": True,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B67 True Wireless Earbuds",
        "sku": "APX-BT-B67",
        "short_description": "Short-stem TWS with 400mAh case, display and cream, black and white programs.",
        "description": "Catalog model B67. Choose a base colorway, then apply logo, packaging artwork, manuals and market-specific labeling.",
        "specifications": "Model B67\nBluetooth 6.0\n400mAh case / 30mAh earbuds\nCream, black, white\nRetail specification panels",
        "moq": "1,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "audio-b67.jpg",
        "is_featured": True,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B64 Wireless Earbuds",
        "sku": "APX-BT-B64",
        "short_description": "Range I TWS model for volume channels with retail packaging and color programs.",
        "description": "Supplied from Wireless Earbuds Model Range I. Suitable for distributors selecting a price-point SKU with private-label packaging.",
        "specifications": "Model B64\nBluetooth 6.0 range\nRetail box artwork\nColor program available\nOEM logo application",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b64.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B70 Wireless Earbuds",
        "sku": "APX-BT-B70",
        "short_description": "Range I companion model with retail packaging and specification panels.",
        "description": "Catalog model B70 for buyers building a multi-SKU audio line under one brand.",
        "specifications": "Model B70\nBluetooth 6.0 range\nRetail packaging\nPrivate-label colors\nDocumentation support",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b70.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B66-B Ear-Hook Earbuds",
        "sku": "APX-BT-B66B",
        "short_description": "Sport ear-hook TWS with 400mAh case, display and gold, white and black finishes.",
        "description": "Catalog model B66-B from Range II. Ear-hook form factor for active-use channels and private-label color programs.",
        "specifications": "Model B66-B\nEar-hook fit\nBluetooth 6.0\n400mAh case\nGold / white / black",
        "moq": "1,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "audio-b66b.jpg",
        "is_featured": True,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B66 Wireless Earbuds",
        "sku": "APX-BT-B66",
        "short_description": "Additional Range II form factor for price-point and channel selection.",
        "description": "Catalog model B66. Pair with B66-B when a buyer wants both standard and ear-hook options in one program.",
        "specifications": "Model B66\nBluetooth 6.0 range\nRetail packaging\nColorways available\nOEM ready",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b66.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B86 Wireless Earbuds",
        "sku": "APX-BT-B86",
        "short_description": "Range II TWS model for mixed-container audio assortments.",
        "description": "Catalog model B86 with retail packaging, specification artwork and private-label color support.",
        "specifications": "Model B86\nBluetooth 6.0 range\nRetail box\nColor program\nCustom logo",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b86.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B85 Wireless Earbuds",
        "sku": "APX-BT-B85",
        "short_description": "Range II companion SKU for channel and price-point selection.",
        "description": "Catalog model B85 from Wireless Earbuds Model Range II.",
        "specifications": "Model B85\nBluetooth 6.0 range\nRetail packaging\nOEM colorways\nDocumentation",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b85.jpg",
        "is_featured": False,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B60 Lifestyle Earbuds",
        "sku": "APX-BT-B60",
        "short_description": "Compact stem TWS with champagne, black and silver cases for lifestyle retail.",
        "description": "Catalog model B60 from Range III. Clean product views for color and finish selection, including silver/ice blue and black/orange programs in the supplied range.",
        "specifications": "Model B60\nBluetooth 6.0\n200mAh case / 30mAh earbuds\nChampagne, black, silver\nLifestyle retail packaging",
        "moq": "1,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "audio-b60.jpg",
        "is_featured": True,
    },
    {
        "category": "bluetooth-headphones",
        "name": "B88 Compact Earbuds",
        "sku": "APX-BT-B88",
        "short_description": "Compact Range III model with retail-packaging and box-content views.",
        "description": "Catalog model B88 for buyers who need a smaller lifestyle SKU alongside B60.",
        "specifications": "Model B88\nCompact TWS\nRetail packaging views\nColor program\nOEM ready",
        "moq": "1,000 pcs",
        "lead_time": "15–30 days",
        "image": "audio-b88.jpg",
        "is_featured": False,
    },
    {
        "category": "power-banks",
        "name": "PD + QC Power Bank Range",
        "sku": "APX-PB-PDQC",
        "short_description": "5,000 to 20,000 mAh portable chargers in slim, magnetic and digital-display formats.",
        "description": "Reliable portable chargers with stable cell supply, accurate capacity labeling and multi-layer safety protection. Logo printing or engraving, custom housing colors and branded gift-box packaging are available.",
        "specifications": "5,000 / 10,000 / 20,000 mAh\nPD and QC fast charging\nLED digital display options\nSlim, mini and magnetic formats\nCE / FCC / RoHS designs",
        "moq": "1,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "power-banks.jpg",
        "is_featured": True,
    },
    {
        "category": "chargers-cables",
        "name": "20–65W Chargers & Cables Set",
        "sku": "APX-CHG-SET",
        "short_description": "Wall chargers, car chargers and braided or PVC cables with global plug options.",
        "description": "A complete charging range spanning GaN and standard wall chargers, car chargers, and Lightning, USB-C and Micro USB cables with reinforced connectors for long bending life.",
        "specifications": "20W to 65W wall and GaN\nCar charger options\nLightning / USB-C / Micro USB\n2.4A / 3A / 5A support\nEU / UK / US / AU plugs",
        "moq": "2,000 pcs",
        "lead_time": "Samples 3–7 days",
        "image": "chargers-cables.jpg",
        "is_featured": True,
    },
]


class Command(BaseCommand):
    help = "Load APEX catalog categories and products from the company catalog."

    def handle(self, *args, **options):
        user_model = get_user_model()
        user, _ = user_model.objects.get_or_create(
            username="admin",
            defaults={"email": "info@apextechnology.com", "is_staff": True, "is_superuser": True},
        )
        user.set_password("ApexAdmin2026!")
        user.is_staff = True
        user.is_superuser = True
        user.save()

        keep_slugs = {item["slug"] for item in CATEGORIES}
        Category.objects.exclude(slug__in=keep_slugs).delete()
        Product.objects.exclude(sku__in={item["sku"] for item in PRODUCTS}).delete()

        categories = {}
        for item in CATEGORIES:
            category, _ = Category.objects.update_or_create(slug=item["slug"], defaults=item)
            categories[category.slug] = category

        source_dir = settings.PROJECT_ROOT / "assets" / "img" / "products"
        media_dir = Path(settings.MEDIA_ROOT) / "products"
        media_dir.mkdir(parents=True, exist_ok=True)

        for item in PRODUCTS:
            payload = dict(item)
            filename = payload.pop("image")
            payload["category"] = categories[payload.pop("category")]
            product, _ = Product.objects.update_or_create(sku=payload["sku"], defaults=payload)
            source = source_dir / filename
            dest_name = f"products/{filename}"
            dest = media_dir / filename
            if source.exists():
                copyfile(source, dest)
                product.image.name = dest_name
                product.save(update_fields=["image"])

        self.stdout.write(self.style.SUCCESS(f"Catalog ready: {Category.objects.count()} categories, {Product.objects.count()} products."))
