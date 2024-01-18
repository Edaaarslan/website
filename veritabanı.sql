-- Yeni bir veritabanı oluşturun veya varsa kullanın
CREATE DATABASE IF NOT EXISTS ProjeYonetimi;
USE ProjeYonetimi;

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS Users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Email VARCHAR(100),
    Username VARCHAR(30),
    Password VARCHAR(100)
);

-- Projeler tablosu
CREATE TABLE IF NOT EXISTS Projects (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ProjectName VARCHAR(100),
    StartDate DATE,
    EndDate DATE,
    FOREIGN KEY (UserID) REFERENCES Users(ID)
);

-- Görevler tablosu
CREATE TABLE IF NOT EXISTS Tasks (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ProjectID INT,
    TaskName VARCHAR(100),
    StartDate DATE,
    EndDate DATE,
    Status ENUM('Tamamlanacak', 'Devam Ediyor', 'Tamamlandı'),
    ManDays INT,  -- Yeni eklenen alan
    FOREIGN KEY (ProjectID) REFERENCES Projects(ID)
);

-- Çalışanlar tablosu
CREATE TABLE IF NOT EXISTS Employees (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Department VARCHAR(50),
    Position VARCHAR(50),
    ManDays INT  -- Yeni eklenen alan
);

-- Çalışan Görevleri tablosu
CREATE TABLE IF NOT EXISTS Employee_Task (
    EmployeeID INT,
    TaskID INT,
    ManDays INT,  -- Yeni eklenen alan
    FOREIGN KEY (EmployeeID) REFERENCES Employees(ID),
    FOREIGN KEY (TaskID) REFERENCES Tasks(ID),
    PRIMARY KEY (EmployeeID, TaskID)
);

-- Kullanıcıları ekleme
INSERT INTO Users (Name, Email, Username, Password) 
SELECT 
    CONCAT('User', n) AS Name,
    CONCAT('user', n, '@example.com') AS Email,
    CONCAT('user', n) AS Username,
    CONCAT('pass', n) AS Password
FROM (
    SELECT n FROM (
        SELECT (a.N + b.N * 10 + c.N * 100) + 1 as n
        FROM 
            (SELECT 0 as N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a,
            (SELECT 0 as N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b,
            (SELECT 0 as N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
    ) numbers
) number_range
LIMIT 100;

-- Projeleri ekleme
INSERT INTO Projects (UserID, ProjectName, StartDate, EndDate)
VALUES 
    (1, 'Proje 1', '2023-01-01', '2023-06-30'),
    (2, 'Proje 2', '2023-02-01', '2023-07-31');
    -- Diğer projeleri ekleyin

-- Görevlere adam günü değerini ekleme
UPDATE Tasks
SET ManDays = ROUND(RAND() * 10); -- Örnek olarak, 0 ile 10 arasında rastgele bir değer ekleniyor

-- Çalışanları ekleme (örnek veriler)
INSERT INTO Employees (Name, Department, Position, ManDays)
VALUES 
    ('Çalışan 1', 'Departman A', 'Pozisyon 1', ROUND(RAND() * 10)),
    ('Çalışan 2', 'Departman B', 'Pozisyon 2', ROUND(RAND() * 10)),
    ('Çalışan 3', 'Departman C', 'Pozisyon 3', ROUND(RAND() * 10));

-- Çalışan Görevlerini ekleme
INSERT INTO Employee_Task (EmployeeID, TaskID, ManDays)
SELECT
    Employees.ID AS EmployeeID,
    Tasks.ID AS TaskID,
    ROUND(RAND() * 5) -- Örnek olarak, 0 ile 5 arasında rastgele bir değer ekleniyor
FROM
    Employees
CROSS JOIN Tasks
ORDER BY RAND()
LIMIT 100;

-- Çalışanlar, Görevler ve Projeleri bağlama
SELECT Employees.Name AS EmployeeName, Projects.ProjectName
FROM Employee_Task
INNER JOIN Employees ON Employee_Task.EmployeeID = Employees.ID
INNER JOIN Tasks ON Employee_Task.TaskID = Tasks.ID
INNER JOIN Projects ON Tasks.ProjectID = Projects.ID;

-- Kullanıcıları göster
SELECT * FROM Users;

-- Projeleri göster
SELECT * FROM Projects;
