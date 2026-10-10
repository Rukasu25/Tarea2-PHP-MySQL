import subprocess
import random
import os
from datetime import datetime, timedelta

# ============================================================
# GENERADOR COMPLETO DE DATOS DE PRUEBA - SALUDUSM
# ============================================================

# Configuración de PHP
RUTA_PHP = r'C:\xampp\php\php.exe'
PASSWORD = 'Password123!'

# ============================================================
# DATOS BASE
# ============================================================

NOMBRES_M = ['Juan', 'Carlos', 'Pedro', 'Luis', 'Diego', 'Jorge', 'Ricardo', 'Andres',
             'Felipe', 'Sebastian', 'Matias', 'Cristobal', 'Alejandro', 'Roberto',
             'Fernando', 'Eduardo', 'Gonzalo', 'Pablo', 'Mauricio', 'Hector']

NOMBRES_F = ['Maria', 'Ana', 'Sofia', 'Camila', 'Valentina', 'Fernanda', 'Catalina',
             'Javiera', 'Constanza', 'Isidora', 'Antonia', 'Florencia', 'Josefa',
             'Martina', 'Amanda', 'Daniela', 'Carolina', 'Patricia', 'Claudia', 'Andrea']

APELLIDOS = ['Gonzalez', 'Munoz', 'Rojas', 'Diaz', 'Perez', 'Soto', 'Contreras',
             'Silva', 'Martinez', 'Sepulveda', 'Morales', 'Rodriguez', 'Lopez',
             'Fuentes', 'Hernandez', 'Torres', 'Araya', 'Flores', 'Espinoza',
             'Valenzuela', 'Castillo', 'Tapia', 'Reyes', 'Gutierrez', 'Castro',
             'Pizarro', 'Alvarez', 'Vasquez', 'Sanchez', 'Fernandez']

MOTIVOS_CONSULTA = [
    'Dolor en el pecho y palpitaciones',
    'Control de presion arterial',
    'Dolor de cabeza intenso',
    'Control de salud general',
    'Dolor abdominal',
    'Dolor en rodilla',
    'Dermatitis en brazos',
    'Control de embarazo',
    'Fiebre y malestar general',
    'Dolor de espalda',
    'Control de nino sano',
    'Vision borrosa',
    'Tos persistente',
    'Dolor de oido',
    'Control de diabetes',
]

OBSERVACIONES = [
    'Paciente presenta sintomas leves. Se recomienda reposo.',
    'Se solicita examen de sangre para confirmar diagnostico.',
    'Paciente estable. Se ajusta medicacion.',
    'Se recomienda control en 15 dias.',
    'Paciente refiere mejoria con tratamiento actual.',
    'Se deriva a especialista para evaluacion.',
    'Sin novedades. Paciente asintomatico.',
    'Se solicita radiografia para descartar fractura.',
    'Paciente con antecedentes familiares. Seguimiento.',
    'Se prescribe tratamiento por 7 dias.',
]

MEDICAMENTOS = [
    'Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Loratadina',
    'Omeprazol', 'Diclofenaco', 'Losartan', 'Metformina',
    'Salbutamol', 'Sertralina', 'Enalapril', 'Aspirina',
    'Prednisona', 'Diazepam', 'Clobetasol'
]

DOSIS = [
    '500 mg cada 8 horas', '400 mg cada 8 horas', '10 mg cada 24 horas',
    '250 mg cada 6 horas', '20 mg cada 24 horas', '50 mg cada 8 horas',
    '100 mg cada 24 horas', '850 mg cada 12 horas', '5 mg cada 8 horas',
    '1 mg cada 24 horas', '40 mg cada 12 horas', '2 puff cada 6 horas'
]

# ============================================================
# FUNCIONES AUXILIARES
# ============================================================

def generar_rut():
    """Genera un RUT único."""
    return f"{random.randint(5000000, 25000000)}-{random.choice('0123456789K')}"

def generar_email(nombre, apellido, dominio):
    """Genera un email único."""
    return f"{nombre.lower()}.{apellido.lower()}{random.randint(1,99999)}@{dominio}"

def generar_fecha_nacimiento(min_edad=18, max_edad=80):
    """Genera fecha de nacimiento."""
    hoy = datetime.now()
    edad = random.randint(min_edad, max_edad)
    fecha = hoy - timedelta(days=edad * 365 + random.randint(0, 364))
    return fecha.strftime('%Y-%m-%d')

def generar_telefono():
    return f"9{random.randint(10000000, 99999999)}"

def generar_hash(password, ruta_php=RUTA_PHP):
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

def escapar_sql(s):
    """Escapa comillas simples."""
    if s is None:
        return 'NULL'
    return s.replace("'", "''")

# ============================================================
# GENERADORES DE USUARIOS
# ============================================================

def generar_usuarios(cantidad, rol, hash_pwd, usar_dominio_correo=False):
    """Genera una lista de usuarios."""
    usuarios = []
    ruts_usados = set()
    emails_usados = set()
    
    for _ in range(cantidad):
        # Generar RUT único
        while True:
            rut = generar_rut()
            if rut not in ruts_usados:
                ruts_usados.add(rut)
                break
        
        # Generar email único
        while True:
            if random.random() < 0.5:
                nombre = random.choice(NOMBRES_M)
                sexo = 'M'
            else:
                nombre = random.choice(NOMBRES_F)
                sexo = 'F'
            
            apellido = random.choice(APELLIDOS)
            
            if usar_dominio_correo or rol == 'PACIENTE':
                dominio = 'correo.cl'
            else:
                dominio = 'saludusm.cl'
            
            email = generar_email(nombre, apellido, dominio)
            if email not in emails_usados:
                emails_usados.add(email)
                break
        
        usuarios.append({
            'email': email,
            'rut': rut,
            'nombre': nombre,
            'apellido': apellido,
            'telefono': generar_telefono(),
            'fecha_nacimiento': generar_fecha_nacimiento(),
            'sexo': sexo,
            'rol': rol,
            'hash': hash_pwd,
        })
    
    return usuarios

# ============================================================
# GENERADOR DE CÓDIGO SQL
# ============================================================

def generar_sql_completo():
    """Genera todo el SQL de poblamiento."""
    
    print("=" * 60)
    print("GENERADOR DE DATOS DE PRUEBA - SALUDUSM")
    print("=" * 60)
    
    # Generar hash
    print("\n🔐 Generando hash con PHP...")
    try:
        hash_pwd = generar_hash(PASSWORD)
        print(f"✅ Hash: {hash_pwd[:60]}...")
    except Exception as e:
        print(f"❌ Error: {e}")
        return None
    
    sql = []
    sql.append("-- ============================================================")
    sql.append("-- DATOS DE PRUEBA - SALUDUSM")
    sql.append("-- Generado automáticamente")
    sql.append(f"-- Contraseña para TODOS: {PASSWORD}")
    sql.append("-- ============================================================")
    sql.append("")
    sql.append("USE saludusm;")
    sql.append("")
    
    # ============================================================
    # 1. CATÁLOGOS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 1. CATÁLOGOS")
    sql.append("-- ============================================================")
    sql.append("")
    
    sql.append("INSERT INTO prevision(nombre) VALUES")
    sql.append("('Fonasa'),('Isapre'),('Particular'),('Otra');")
    sql.append("")
    
    sql.append("INSERT INTO especialidad(nombre) VALUES")
    sql.append("('Medicina General'),('Cardiologia'),('Pediatria'),('Dermatologia'),")
    sql.append("('Traumatologia'),('Neurologia'),('Oftalmologia'),('Ginecologia');")
    sql.append("")
    
    sql.append("INSERT INTO estado_cita(nombre) VALUES")
    sql.append("('Reservada'),('Confirmada'),('Atendida'),('No Asistio'),('Cancelada');")
    sql.append("")
    
    sql.append("INSERT INTO centro_medico(codigo,nombre,comuna,region) VALUES")
    sql.append("('C001','Centro SaludUSM Casa Central','Valparaiso','Valparaiso'),")
    sql.append("('C002','Centro SaludUSM San Joaquin','San Joaquin','Metropolitana'),")
    sql.append("('C003','Centro SaludUSM Maipu','Maipu','Metropolitana'),")
    sql.append("('C004','Centro SaludUSM Concepcion','Concepcion','Biobio');")
    sql.append("")
    
    sql.append("INSERT INTO diagnostico(codigo_cie10,descripcion) VALUES")
    diagnosticos = [
        ('J00', 'Rinofaringitis aguda'),
        ('J06.9', 'Infeccion respiratoria aguda'),
        ('I10', 'Hipertension esencial'),
        ('M54.5', 'Dolor lumbar'),
        ('L20.9', 'Dermatitis atopica'),
        ('R51', 'Cefalea'),
        ('K21.9', 'Reflujo gastroesofagico'),
        ('E11', 'Diabetes mellitus tipo 2'),
        ('J45', 'Asma'),
        ('F41.9', 'Trastorno de ansiedad'),
        ('M23.2', 'Desgarro de menisco'),
        ('H66.9', 'Otitis media'),
    ]
    for i, (codigo, desc) in enumerate(diagnosticos):
        sep = ';' if i == len(diagnosticos) - 1 else ','
        sql.append(f"('{codigo}','{desc}'){sep}")
    sql.append("")
    
    sql.append("INSERT INTO medicamento(nombre) VALUES")
    for i, med in enumerate(MEDICAMENTOS):
        sep = ';' if i == len(MEDICAMENTOS) - 1 else ','
        sql.append(f"('{med}'){sep}")
    sql.append("")
    
    # ============================================================
    # 2. USUARIOS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 2. USUARIOS (contraseña: Password123!)")
    sql.append("-- ============================================================")
    sql.append("")
    
    CANT_ADMINS = 2
    CANT_MEDICOS = 8
    CANT_PACIENTES = 20
    
    print(f"\n📝 Generando {CANT_ADMINS} admins...")
    admins = generar_usuarios(CANT_ADMINS, 'ADMIN', hash_pwd)
    
    print(f"📝 Generando {CANT_MEDICOS} médicos...")
    medicos = generar_usuarios(CANT_MEDICOS, 'MEDICO', hash_pwd)
    
    print(f"📝 Generando {CANT_PACIENTES} pacientes...")
    pacientes = generar_usuarios(CANT_PACIENTES, 'PACIENTE', hash_pwd)
    
    # Admins
    sql.append("-- Admins")
    for u in admins:
        sql.append(
            f"INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES "
            f"('{escapar_sql(u['email'])}','{u['rut']}','{escapar_sql(u['nombre'])}','{escapar_sql(u['apellido'])}',"
            f"'{u['telefono']}','{u['fecha_nacimiento']}','{u['sexo']}','{u['hash']}','ADMIN');"
        )
    sql.append("")
    
    # Médicos
    sql.append("-- Médicos")
    for u in medicos:
        sql.append(
            f"INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES "
            f"('{escapar_sql(u['email'])}','{u['rut']}','{escapar_sql(u['nombre'])}','{escapar_sql(u['apellido'])}',"
            f"'{u['telefono']}','{u['fecha_nacimiento']}','{u['sexo']}','{u['hash']}','MEDICO');"
        )
    sql.append("")
    
    # Pacientes
    sql.append("-- Pacientes")
    for u in pacientes:
        sql.append(
            f"INSERT INTO usuario(email,rut,nombre,apellido,telefono,fecha_nacimiento,sexo,password_hash,rol) VALUES "
            f"('{escapar_sql(u['email'])}','{u['rut']}','{escapar_sql(u['nombre'])}','{escapar_sql(u['apellido'])}',"
            f"'{u['telefono']}','{u['fecha_nacimiento']}','{u['sexo']}','{u['hash']}','PACIENTE');"
        )
    sql.append("")
    
    # ============================================================
    # 3. PACIENTES Y MEDICOS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 3. REGISTROS EN TABLAS PACIENTE Y MEDICO")
    sql.append("-- ============================================================")
    sql.append("")
    
    sql.append("-- Insertar en tabla paciente")
    for u in pacientes:
        prevision = random.randint(1, 4)
        sql.append(f"INSERT INTO paciente(email, id_prevision) VALUES ('{escapar_sql(u['email'])}', {prevision});")
    sql.append("")
    
    sql.append("-- Insertar en tabla medico")
    for u in medicos:
        sql.append(f"INSERT INTO medico(email) VALUES ('{escapar_sql(u['email'])}');")
    sql.append("")
    
    # ============================================================
    # 4. ESPECIALIDADES Y CENTROS DE MEDICOS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 4. ESPECIALIDADES Y CENTROS DE MEDICOS")
    sql.append("-- ============================================================")
    sql.append("")
    sql.append("-- NOTA: Los id_medico se asignan automáticamente.")
    sql.append("-- Estos INSERT asumen que los médicos tienen IDs 1-{}".format(CANT_MEDICOS))
    sql.append("-- Ajusta si ya tenías médicos en la BD.")
    sql.append("")
    
    sql.append("-- Asignar especialidades (1-3 por médico)")
    for i in range(1, CANT_MEDICOS + 1):
        num_esp = random.randint(1, 3)
        esps = random.sample(range(1, 9), num_esp)
        for e in esps:
            sql.append(f"INSERT INTO medico_especialidad(id_medico, id_especialidad) VALUES ({i}, {e});")
    sql.append("")
    
    sql.append("-- Asignar centros (1-3 por médico)")
    for i in range(1, CANT_MEDICOS + 1):
        num_cent = random.randint(1, 3)
        cents = random.sample(range(1, 5), num_cent)
        for c in cents:
            sql.append(f"INSERT INTO medico_centro(id_medico, id_centro) VALUES ({i}, {c});")
    sql.append("")
    
    # ============================================================
    # 5. CITAS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 5. CITAS")
    sql.append("-- ============================================================")
    sql.append("")
    sql.append("-- id_estado: 1=Reservada, 2=Confirmada, 3=Atendida, 4=No Asistio, 5=Cancelada")
    sql.append("-- id_paciente: 1-{}  |  id_medico: 1-{}".format(CANT_PACIENTES, CANT_MEDICOS))
    sql.append("")
    
    # Generar citas variadas
    citas_generadas = []
    
    # Citas ATENDIDAS (pasadas)
    sql.append("-- Citas ATENDIDAS (para historial)")
    for _ in range(30):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_atras = random.randint(30, 180)
        fecha = (datetime.now() - timedelta(days=dias_atras)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', 3);"
        )
        citas_generadas.append(('atendida', fecha))
    sql.append("")
    
    # Citas RESERVADAS (futuras)
    sql.append("-- Citas RESERVADAS (futuras)")
    for _ in range(15):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_adelante = random.randint(1, 60)
        fecha = (datetime.now() + timedelta(days=dias_adelante)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', 1);"
        )
    sql.append("")
    
    # Citas CONFIRMADAS (futuras)
    sql.append("-- Citas CONFIRMADAS (futuras)")
    for _ in range(10):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_adelante = random.randint(1, 30)
        fecha = (datetime.now() + timedelta(days=dias_adelante)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', 2);"
        )
    sql.append("")
    
    # Citas NO ASISTIO
    sql.append("-- Citas NO ASISTIO")
    for _ in range(8):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_atras = random.randint(10, 90)
        fecha = (datetime.now() - timedelta(days=dias_atras)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', 4);"
        )
    sql.append("")
    
    # Citas CANCELADAS
    sql.append("-- Citas CANCELADAS")
    for _ in range(5):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_atras = random.randint(1, 30)
        fecha = (datetime.now() - timedelta(days=dias_atras)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', 5);"
        )
    sql.append("")
    
    # Citas VENCIDAS (para probar sp_cancelar_citas_vencidas)
    sql.append("-- Citas VENCIDAS (Reservada/Confirmada en el pasado)")
    for _ in range(5):
        id_pac = random.randint(1, CANT_PACIENTES)
        id_med = random.randint(1, CANT_MEDICOS)
        id_esp = random.randint(1, 8)
        id_cent = random.randint(1, 4)
        dias_atras = random.randint(5, 30)
        fecha = (datetime.now() - timedelta(days=dias_atras)).strftime('%Y-%m-%d')
        hora = f"{random.randint(8,17):02d}:{random.choice(['00','30'])}:00"
        estado = random.choice([1, 2])
        sql.append(
            f"INSERT INTO cita(id_paciente, id_medico, id_especialidad, id_centro, fecha_hora, id_estado) VALUES "
            f"({id_pac}, {id_med}, {id_esp}, {id_cent}, '{fecha} {hora}', {estado});"
        )
    sql.append("")
    
    # ============================================================
    # 6. ATENCIONES (solo para citas Atendidas)
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 6. ATENCIONES")
    sql.append("-- ============================================================")
    sql.append("")
    sql.append("-- NOTA: Solo para citas con estado Atendida (id_estado=3)")
    sql.append("-- Los id_cita se asignan automáticamente.")
    sql.append("-- Este INSERT asume que las citas atendidas tienen IDs 1-30.")
    sql.append("-- Ajusta según tu BD.")
    sql.append("")
    
    for id_cita in range(1, 31):
        motivo = random.choice(MOTIVOS_CONSULTA)
        obs = random.choice(OBSERVACIONES)
        sql.append(
            f"INSERT INTO atencion(id_cita, motivo, observaciones) VALUES "
            f"({id_cita}, '{escapar_sql(motivo)}', '{escapar_sql(obs)}');"
        )
    sql.append("")
    
    # ============================================================
    # 7. DIAGNOSTICOS POR ATENCION
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 7. DIAGNOSTICOS POR ATENCION")
    sql.append("-- ============================================================")
    sql.append("")
    
    for id_atencion in range(1, 31):
        num_diag = random.randint(1, 2)
        diags = random.sample(range(1, 13), num_diag)  # 12 diagnósticos
        for d in diags:
            sql.append(f"INSERT INTO diagnostico_atencion(id_atencion, id_diagnostico) VALUES ({id_atencion}, {d});")
    sql.append("")
    
    # ============================================================
    # 8. RECETAS
    # ============================================================
    sql.append("-- ============================================================")
    sql.append("-- 8. RECETAS")
    sql.append("-- ============================================================")
    sql.append("")
    
    for id_atencion in range(1, 31):
        num_recetas = random.randint(1, 2)
        meds = random.sample(range(1, len(MEDICAMENTOS) + 1), num_recetas)
        for m in meds:
            dosis = random.choice(DOSIS)
            dias = random.randint(3, 30)
            sql.append(
                f"INSERT INTO receta_linea(id_atencion, id_medicamento, dosis, dias_tratamiento) VALUES "
                f"({id_atencion}, {m}, '{escapar_sql(dosis)}', {dias});"
            )
    sql.append("")
    
    sql.append("-- ============================================================")
    sql.append("-- FIN DE LOS DATOS DE PRUEBA")
    sql.append("-- ============================================================")
    
    return "\n".join(sql), admins, medicos, pacientes

# ============================================================
# MAIN
# ============================================================

if __name__ == "__main__":
    resultado = generar_sql_completo()
    
    if resultado is None:
        exit(1)
    
    sql, admins, medicos, pacientes = resultado
    
    # Guardar en la misma carpeta del script
    carpeta = os.path.dirname(os.path.abspath(__file__))
    ruta_archivo = os.path.join(carpeta, 'poblamiento_completo.sql')
    
    with open(ruta_archivo, 'w', encoding='utf-8') as f:
        f.write(sql)
    
    print("\n" + "=" * 60)
    print("✅ ARCHIVO GENERADO")
    print("=" * 60)
    print(f"📁 Ubicación: {ruta_archivo}")
    print(f"📄 Total de líneas: {len(sql.splitlines())}")
    print(f"\n📊 Resumen:")
    print(f"   Admins:     {len(admins)}")
    print(f"   Médicos:    {len(medicos)}")
    print(f"   Pacientes:  {len(pacientes)}")
    print(f"   Citas:      ~73 (30 atendidas + 15 reservadas + 10 confirmadas + 8 no asistió + 5 canceladas + 5 vencidas)")
    print(f"   Atenciones: 30")
    print(f"   Recetas:    ~60")
    
    print("\n" + "=" * 60)
    print(f"🔑 CONTRASEÑA PARA TODOS: {PASSWORD}")
    print("=" * 60)
    
    print("\n📧 ADMINS:")
    for u in admins:
        print(f"   {u['email']}")
    
    print("\n📧 MÉDICOS:")
    for u in medicos:
        print(f"   {u['email']}")
    
    print("\n📧 PACIENTES:")
    for u in pacientes[:5]:
        print(f"   {u['email']}")
    if len(pacientes) > 5:
        print(f"   ... y {len(pacientes) - 5} más")
    
    print("\n" + "=" * 60)
    print("📋 SIGUIENTE PASO:")
    print("=" * 60)
    print("1. Abre phpMyAdmin")
    print("2. Selecciona la BD 'saludusm'")
    print("3. Ve a la pestaña SQL")
    print(f"4. Copia y pega el contenido de '{os.path.basename(ruta_archivo)}'")
    print("5. Ejecuta")
    print("6. Prueba el login con cualquiera de los emails generados")
    print("=" * 60)