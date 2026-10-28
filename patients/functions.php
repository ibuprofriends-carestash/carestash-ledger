<?php
    class Patient{
        public function displayPatientInfo(){
            $fetch = "SELECT * FROM users WHERE id = :uid";
            $prepare = $pdo->prepare($fetch);
            $prepare->bindParam(':uid', $uid, PDO::PARAM_INT);
            $prepare->execute();
            $result = $prepare->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public function updatePatientInfo($uid, $username, $email){
            $update = "UPDATE users SET username = :username, email = :email WHERE id = :uid";
            $prepare = $pdo->prepare($update);
            $prepare->bindParam(':uid', $uid, PDO::PARAM_INT);
            $prepare->bindParam(':username', $username, PDO::PARAM_STR);
            $prepare->bindParam(':email', $email, PDO::PARAM_STR);
            $prepare->bindParam(':phone', $phone, PDO::PARAM_STR);
            return $prepare->execute();
        }

        public function changePassword($uid, $newPassword){
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = "UPDATE users SET password = :password WHERE id = :uid";
            $prepare = $pdo->prepare($update);
            $prepare->bindParam(':uid', $uid, PDO::PARAM_INT);
            $prepare->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            return $prepare->execute();
        }

        public function addDoctor($uid, $doctorName, $doctorEmail, $doctorPhone){
            $insert = "INSERT INTO doctors (user_id, name, email, phone) VALUES (:uid, :name, :email, :phone)";
            $prepare = $pdo->prepare($insert);
            $prepare->bindParam(':uid', $uid, PDO::PARAM_INT);
            $prepare->bindParam(':name', $doctorName, PDO::PARAM_STR);
            $prepare->bindParam(':email', $doctorEmail, PDO::PARAM_STR);
            $prepare->bindParam(':phone', $doctorPhone, PDO::PARAM_STR);
            return $prepare->execute();
        }
    }