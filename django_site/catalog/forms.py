from django import forms
from .models import Inquiry


class InquiryForm(forms.ModelForm):
    class Meta:
        model = Inquiry
        fields = ["name", "email", "country", "product_interest", "quantity", "message"]
        widgets = {
            "name": forms.TextInput(attrs={"placeholder": "Your name", "required": True}),
            "email": forms.EmailInput(attrs={"placeholder": "Company email", "required": True}),
            "country": forms.TextInput(attrs={"placeholder": "Country or region"}),
            "quantity": forms.TextInput(attrs={"placeholder": "Estimated quantity"}),
            "message": forms.Textarea(
                attrs={
                    "rows": 5,
                    "placeholder": "Product, target price, specifications, branding or packaging",
                    "required": True,
                }
            ),
        }

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        for field in self.fields.values():
            field.widget.attrs.setdefault("class", "field-input")
