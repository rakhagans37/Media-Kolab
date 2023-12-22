<?php
require_once __DIR__ . "/getConnectionMsqli.php";
require_once __DIR__ . "/getConnection.php";
require_once __DIR__ . "/cloudinary.php";
require_once __DIR__ . "/hash.php";

function setNewJob($vacancy_id, $vacancyTitle, $vacancyContent, $company, $requirement, $dateRelease, $categoryId, $editorId, $imageUrl, $companyLogo)
{
  $conn = getConnection();

  $sql = "INSERT INTO tb_job_vacancies (vacancy_id, vacancy_title, vacancy_content, company_name, vacancy_requirement, date_release, 
		category_id, editor_id, image_url, logo) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $request = $conn->prepare($sql);
  $request->bindParam(1, $vacancy_id);
  $request->bindParam(2, $vacancyTitle);
  $request->bindParam(3, $vacancyContent);
  $request->bindParam(4, $company);
  $request->bindParam(5, $requirement);
  $request->bindParam(6, $dateRelease);
  $request->bindParam(7, $categoryId);
  $request->bindParam(8, $editorId);
  $request->bindParam(9, $imageUrl);
  $request->bindParam(10, $companyLogo);
  $request->execute();

  $conn = null;
}

function getJobByEditor($editorId)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_job_vacancies WHERE editor_id = :editorId";

  $request = $conn->prepare($sql);
  $request->bindParam("editorId", $editorId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getSearchJobByEditor($editorId, $searchParam)
{
  $conn = getConnection();

  $sql = "SELECT * FROM tb_job_vacancies WHERE editor_id = :editorId AND vacancy_title LIKE '%$searchParam%'";

  $request = $conn->prepare($sql);
  $request->bindParam("editorId", $editorId);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function deleteJobVacancies($jobId)
{
  $conn = getConnection();

  $sql = "DELETE FROM tb_job_vacancies WHERE vacancy_id = ?";
  $request = $conn->prepare($sql);
  $request->bindParam(1, $jobId);
  $request->execute();

  $conn = null;
}

function deleteJobTag($jobId)
{
  $conn = getConnection();

  $sqlDelete = "DELETE FROM tb_job_tag WHERE vacancy_id = ?";
  $request = $conn->prepare($sqlDelete);
  $request->bindParam(1, $jobId);
  $request->execute();

  $conn = null;
}

function updateJobTitle($jobId, $newTitle)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_job_vacancies SET 
					vacancy_title = :updateTitle
					WHERE vacancy_id = :jobId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("updateTitle", $newTitle);
  $request->bindParam("jobId", $jobId);
  $request->execute();

  $conn = null;
}

function updateJobCategory($jobId, $newCategory)
{
  $conn = getConnection();

  $sqlUpdate = "UPDATE tb_job_vacancies SET 
					category_id = :categoryId
					WHERE vacancy_id = :jobId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("categoryId", $newCategory);
  $request->bindParam("jobId", $jobId);
  $request->execute();

  $conn = null;
}

function setJobCompanyLogoToNull($vacancyId)
{
  $conn = getConnection();
  $newImage = null;

  $sqlUpdate = "UPDATE tb_job_vacancies SET 
					logo = :newImage
					WHERE vacancy_id = :vacancyId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("newImage", $newImage);
  $request->bindParam("vacancyId", $vacancyId);
  $request->execute();

  $conn = null;
}

function setJobImageToNull($vacancyId)
{
  $conn = getConnection();
  $newImage = null;

  $sqlUpdate = "UPDATE tb_job_vacancies SET 
					image_url = :newImage
					WHERE vacancy_id = :vacancyId";

  $request = $conn->prepare($sqlUpdate);
  $request->bindParam("newImage", $newImage);
  $request->bindParam("vacancyId", $vacancyId);
  $request->execute();

  $conn = null;
}

function deleteJobImage($vacancyId)
{
  $conn = getConnection();

  $sqlGetImage = "SELECT image_url, logo FROM tb_job_vacancies WHERE vacancy_id = :vacancyId";

  $request = $conn->prepare($sqlGetImage);
  $request->bindParam("vacancyId", $vacancyId);
  $request->execute();
  $result = $request->fetch();

  if (!is_null($result['image_url']) && !is_null($result['logo'])) {
    deleteImage(decryptPhotoProfile($result['image_url']));
    deleteImage(decryptPhotoProfile($result['logo']));
  }

  $conn = null;
}

function getAllSearchJob($jobSearch)
{
  $conn = getConnection();
  $sql = "SELECT tb_job_vacancies.vacancy_id, tb_job_vacancies.vacancy_title, tb_category_job_vacancy.category_name, tb_job_vacancies.date_release, tb_editor.username, tb_job_vacancies.views, tb_job_vacancies.company_name FROM ((tb_job_vacancies INNER JOIN tb_category_job_vacancy ON tb_job_vacancies.category_id = tb_category_job_vacancy.category_id) INNER JOIN tb_editor ON tb_job_vacancies.editor_id = tb_editor.editor_id) WHERE tb_job_vacancies.vacancy_title LIKE '%$jobSearch%'";

  $request = $conn->prepare($sql);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}

function getAllJob()
{
  $conn = getConnection();
  $sql = "SELECT tb_job_vacancies.vacancy_id, tb_job_vacancies.vacancy_title, tb_category_job_vacancy.category_name, tb_job_vacancies.date_release, tb_editor.username, tb_job_vacancies.views, tb_job_vacancies.company_name FROM ((tb_job_vacancies INNER JOIN tb_category_job_vacancy ON tb_job_vacancies.category_id = tb_category_job_vacancy.category_id) INNER JOIN tb_editor ON tb_job_vacancies.editor_id = tb_editor.editor_id)";

  $request = $conn->prepare($sql);
  $request->execute();

  if ($result = $request->fetchAll()) {
    $conn = null;
    return $result;
  } else {
    $conn = null;
    return array();
  }
}
