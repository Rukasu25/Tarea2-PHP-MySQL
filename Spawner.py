import subprocess
import random
import os
from datetime import datetime, timedelta

def generar_hash(password, ruta_php=r'C:\xampp\php\php.exe'):
    """Genera el hash usando PHP."""
    try:
        comando = [
            ruta_php, '-r',
            f"echo password_hash('{password}', PASSWORD_DEFAULT);"
        ]
        resultado = subprocess.run(comando, capture_output=True, text=True, timeout=10)
        
        if resultado.returncode != 0:
            raise Exception(resultado.stderr)
        
        hash_gen = resultado.stdout.strip()
        if not hash_gen.startswith('$2y$'):
            raise Exception(f"Hash inválido: {hash_gen}")
        
        return hash_gen
    except FileNotFoundError:
        raise Exception(f"PHP no encontrado en: {ruta_php}")

# ============================================================
# DATOS
# ============================================================

NOMBRES_M = ['Juan', 'Carlos', 'Pedro', 'Luis', 'Diego', 'Jorge', 'Ricardo']
NOMBRES_F = ['Maria', 'Ana', 'Sofia', 'Camila', 'Valentina', 'Fernanda']
APELLIDOS = ['Gonzalez', 'Munoz', 'Rojas', 'Diaz', 'Perez', 'Soto', 'Silva']

def generar_rut():
    return f"{random.randint(5000000, 25000000)}-{random.choice('0123456789K')}"

def generar_usuario(rol, hash_pwd):
    if rol == 'PACIENTE':
        nombre = random.choice(NOMBRES_M + NOMBRES_F)
        dominio = 'correo.cl'
    else:
        nombre = random.choice(NOMBRES_M + NOMBRES_F)
        dominio = 'saludusm.cl'
    
    apellido = random.choice(APELLIDOS)
    email = f"{nombre.lower()}.{apellido.lower()}{random.randint(1,999)}@{dominio}"
    
    fecha_nac = (datetime.now() - timedelta(days=random.randint(25*365, 65*365))).strftime('%Y-%m-%d')
    
    return {
        'email': email,
        'rut': generar_rut(),
        'nombre': nombre,
        'apellido': apellido,
        'telefono': f"9{random.randint(10000000, 99999999)}",
        'fecha_nacimiento': fecha_nac,
        'sexo': 'M' if nombre in NOMBRES_M else 'F',
        'rol': rol,
        'hash': hash_pwd,
    }

# ============================================================
# MAIN
# ============================================================

if __name__ == "__main__":
    print("=" * 60)
    print("GENERADOR DE USUARIOS - SALUDUSM")
    print("=" * 60)

    PASSWORD = 'Password123!'
    CANT_ADMINS = 2
    CANT_MEDICOS = 8
    CANT_PACIENTES = 15

    # aqui se genera el hash
    print("\n🔐 Generando hash con PHP...")
    try:
        hash_pwd = generar_hash(PASSWORD)
        print(f"✅ Hash: {hash_pwd[:60]}...")
    except Exception as e:
        print(f"❌ Error: {e}")
        exit(1)

    # aqui genearamos el SQL
    print("\nGenerando SQL...")
    sql = ["USE saludusm;\n"]
    
    usuarios = []
    
    for rol, cantidad in [('ADMIN', CANT_ADMINS), ('MEDICO', CANT_MEDICOS), ('PACIENTE', CANT_PACIENTES)]:
        sql.append(f"\n-- ========== {rol}S ==========")
        for _ in range(cantidad):
            u = generar_usuario(rol, hash_pwd)
            usuarios.append(u)
            sql.append(
                f"INSERT INTO usuario(email,rut,nombre,apellido,telefono,"
                f"fecha_nacimiento,sexo,password_hash,rol) VALUES "
                f"('{u['email']}','{u['rut']}','{u['nombre']}','{u['apellido']}',"
                f"'{u['telefono']}','{u['fecha_nacimiento']}','{u['sexo']}',"
                f"'{u['hash']}','{u['rol']}');"
            )
    
    # Insertar en tabla medico
    sql.append("\n-- ========== REGISTROS EN TABLA MEDICO ==========")
    for u in usuarios:
        if u['rol'] == 'MEDICO':
            sql.append(f"INSERT INTO medico(email) VALUES ('{u['email']}');")
    
    # Insertar en tabla paciente
    sql.append("\n-- ========== REGISTROS EN TABLA PACIENTE ==========")
    for u in usuarios:
        if u['rol'] == 'PACIENTE':
            prevision = random.randint(1, 4)
            sql.append(f"INSERT INTO paciente(email, id_prevision) VALUES ('{u['email']}', {prevision});")
    
    # ============================================================
    # GUARDAR ARCHIVO
    # ============================================================
    
    # Opción: guardar en la MISMA carpeta del script
    carpeta_script = os.path.dirname(os.path.abspath(__file__))
    ruta_archivo = os.path.join(carpeta_script, 'datos_generados.sql')
    
    with open(ruta_archivo, 'w', encoding='utf-8') as f:
        f.write("\n".join(sql))
    
    print(f"\nArchivo generado")
    print(f"Ubicación: {ruta_archivo}")
    print(f"Total de líneas: {len(sql)}")
    print(f"Usuarios generados: {len(usuarios)}")
    print(f"   - Admins:    {CANT_ADMINS}")
    print(f"   - Médicos:   {CANT_MEDICOS}")
    print(f"   - Pacientes: {CANT_PACIENTES}")
    print(f"\nContraseña para todos: {PASSWORD}")
    
    print("\n" + "=" * 60)
    print("EMAILS GENERADOS:")
    print("=" * 60)
    for u in usuarios:
        print(f"  [{u['rol']:8}] {u['email']}")