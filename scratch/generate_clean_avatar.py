import os
from PIL import Image, ImageDraw

def create_clean_avatar(output_path):
    size = (512, 512)
    bg_color = (104, 66, 194) 
    image = Image.new('RGB', size, bg_color)
    draw = ImageDraw.Draw(image)

    # Circle head
    center_x = 256
    head_y = 190
    head_radius = 85
    draw.ellipse(
        [center_x - head_radius, head_y - head_radius, center_x + head_radius, head_y + head_radius],
        fill=(255, 255, 255)
    )

    # Body shoulders arc
    shoulder_y = 390
    shoulder_rx = 150
    shoulder_ry = 120
    draw.ellipse(
        [center_x - shoulder_rx, shoulder_y - shoulder_ry, center_x + shoulder_rx, shoulder_y + shoulder_ry],
        fill=(255, 255, 255)
    )

    fmt = 'JPEG' if output_path.endswith('.jpg') else 'PNG'
    image.save(output_path, fmt)

if __name__ == '__main__':
    base_dir = r"c:\xampp\htdocs\2025\projeto-final"
    files = [
        os.path.join(base_dir, "assets", "img", "default_avatar.jpg"),
        os.path.join(base_dir, "assets", "img", "default_student.png"),
        os.path.join(base_dir, "default_student.png"),
        os.path.join(base_dir, "assets", "img", "logo_mascot.png"),
        os.path.join(base_dir, "assets", "img", "mascot.png")
    ]
    for f in files:
        create_clean_avatar(f)
        print(f"Updated {f}")
