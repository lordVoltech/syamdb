def vigenere_encrypt(plaintext, key):
    result = ""
    key = key.upper().replace(" ", "")
    key_index = 0

    for char in plaintext.upper():
        if char.isalpha():
            shift = ord(key[key_index % len(key)]) - ord('A')
            encrypted_char = chr((ord(char) - ord('A') + shift) % 26 + ord('A'))
            result += encrypted_char
            key_index += 1
        elif char == " ":
            result += "*"  # Ganti spasi jadi bintang
        else:
            result += char  # Simpan karakter non-alfabet

    return result

def block_transposition(text, block_count=3):
    block_size = len(text) // block_count
    blocks = [text[i*block_size:(i+1)*block_size] for i in range(block_count - 1)]
    blocks.append(text[(block_count - 1)*block_size:])  # Sisa ke blok terakhir

    # Contoh transposisi: tukar blok pertama dan terakhir
    blocks[0], blocks[-1] = blocks[-1], blocks[0]
    return ''.join(blocks)

# Contoh penggunaan
plaintext = "I LOVE YOU"
key = "RAMEN"

# Step 1 & 2: Enkripsi + ganti spasi
encrypted = vigenere_encrypt(plaintext, key)
print("Setelah Vigenère & spasi jadi '*':", encrypted)

# Step 3 & 4: Transposisi blok
final_result = block_transposition(encrypted)
print("Hasil akhir:", final_result)