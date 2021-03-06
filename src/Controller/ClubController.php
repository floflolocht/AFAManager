<?php
// src/Controller/ClubController.php
namespace App\Controller;

use App\Entity\Club;
use App\Entity\ClubTeacher;
use App\Entity\Grade;
use App\Entity\Member;
use App\Entity\Training;
use App\Entity\TrainingAddress;
use App\Entity\User;

use App\Form\ClubType;
use App\Form\GradeType;
use App\Form\UserType;

use App\Service\ClubTools;
use App\Service\MemberTools;
use App\Service\UserTools;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/club-", name="club_")
 *
 * @IsGranted("ROLE_CLUB")
 */
class ClubController extends AbstractController
{
    /**
     * @Route("{club<\d+>}/index_dojo", name="dojo_index")
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function dojoIndex(ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        return $this->render('Club/Dojo/index.html.twig', array('clubTools' => $clubTools));
    }

    /**
     * @Route("{club<\d+>}/ajouter_dojo", name="dojo_address_add")
     * @param Request $request
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoAddressAdd(Request $request, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, new TrainingAddress(), array('form' => 'dojo_create', 'data_class' => TrainingAddress::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoAddress($form->getData(), 'Add');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/address_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/modifier_dojo/{address<\d+>}", name="dojo_address_update")
     * @param Request $request
     * @param TrainingAddress $address
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoAddressUpdate(Request $request, TrainingAddress $address, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $address, array('form' => 'dojo_update', 'data_class' => TrainingAddress::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoAddress($form->getData());

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/address_update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/supprimer_dojo/{address<\d+>}", name="dojo_address_delete")
     * @param Request $request
     * @param TrainingAddress $address
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoAddressDelete(Request $request, TrainingAddress $address, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $address, array('form' => 'dojo_delete', 'data_class' => TrainingAddress::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoAddress($form->getData(), 'Delete');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/address_delete.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/ajouter_horaire", name="dojo_training_add")
     * @param Request $request
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTrainingAdd(Request $request, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, new Training(), array('form' => 'training_create', 'data_class' => Training::class, 'choices' => $clubTools->getClub()->getClubAddresses()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTraining($form->getData(), 'Add');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/training_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/modifier_horaire/{training<\d+>}", name="dojo_training_update")
     * @param Request $request
     * @param Training $training
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTrainingUpdate(Request $request, Training $training, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $training, array('form' => 'training_update', 'data_class' => Training::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTraining($form->getData());

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/training_update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/supprimer_horaire/{training<\d+>}", name="dojo_training_delete")
     * @param Request $request
     * @param Training $training
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTrainingDelete(Request $request, Training $training, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $training, array('form' => 'training_delete', 'data_class' => Training::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTraining($form->getData(), 'Delete');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/training_delete.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/ajouter_professeur_afa", name="dojo_teacher_afa_add")
     * @param Request $request
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherAFAAdd(Request $request, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, new ClubTeacher(), array('form' => 'teacher_afa_create', 'data_class' => ClubTeacher::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData(), 'Add', $form->get('ClubTeacherMember')->getData());

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_afa_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/modifier_professeur_afa/{teacher<\d+>}", name="dojo_teacher_afa_update")
     * @param Request $request
     * @param ClubTeacher $teacher
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherAFAUpdate(Request $request, ClubTeacher $teacher, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $teacher, array('form' => 'teacher_afa_update', 'data_class' => ClubTeacher::class));

        $form->get('ClubTeacherMember')->setData($teacher->getClubTeacherMember()->getMemberId());
        $form->get('ClubTeacherFirstname')->setData($teacher->getClubTeacherMember()->getMemberFirstname());
        $form->get('ClubTeacherName')->setData($teacher->getClubTeacherMember()->getMemberName());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData());

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_afa_update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/supprimer_professeur_afa/{teacher<\d+>}", name="dojo_teacher_afa_delete")
     * @param Request $request
     * @param ClubTeacher $teacher
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherAFADelete(Request $request, ClubTeacher $teacher, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $teacher, array('form' => 'teacher_afa_delete', 'data_class' => ClubTeacher::class));

        $form->get('ClubTeacherMember')->setData($teacher->getClubTeacherMember()->getMemberId());
        $form->get('ClubTeacherFirstname')->setData($teacher->getClubTeacherMember()->getMemberFirstname());
        $form->get('ClubTeacherName')->setData($teacher->getClubTeacherMember()->getMemberName());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData(), 'Delete');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_afa_delete.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/ajouter_professeur_etranger", name="dojo_teacher_foreign_add")
     * @param Request $request
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherForeignAdd(Request $request, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, new ClubTeacher(), array('form' => 'teacher_foreign_create', 'data_class' => ClubTeacher::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData(), 'Add');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_foreign_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/modifier_professeur_etranger/{teacher<\d+>}", name="dojo_teacher_foreign_update")
     * @param Request $request
     * @param ClubTeacher $teacher
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherForeignUpdate(Request $request, ClubTeacher $teacher, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $teacher, array('form' => 'teacher_foreign_update', 'data_class' => ClubTeacher::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData());

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_foreign_update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/supprimer_professeur_etranger/{teacher<\d+>}", name="dojo_teacher_foreign_delete")
     * @param Request $request
     * @param ClubTeacher $teacher
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function dojoTeacherForeignDelete(Request $request, ClubTeacher $teacher, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $teacher, array('form' => 'teacher_foreign_delete', 'data_class' => ClubTeacher::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->dojoTeacher($form->getData(), 'Delete');

            return $this->redirectToRoute('club_dojo_index');
        }

        return $this->render('Club/Dojo/teacher_foreign_delete.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/detail_association", name="association_details")
     * @param Request $request
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function associationDetails(Request $request, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $form = $this->createForm(ClubType::class, $clubTools->getClub(), array('form' => 'detail_association'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $clubTools->associationDetails($form->getData());

            return $this->redirectToRoute('common_index');
        }

        return $this->render('Club/Association/details.html.twig', array('form' => $form->createView(), 'club' => $clubTools->getClub()));
    }

    /**
     * @Route("{club<\d+>}/liste_des_membres", name="members_list")
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function membersList(ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        $members = $this->getDoctrine()->getRepository(Member::class)->getClubActiveMembers($clubTools->getClub());

        $old_members = $this->getDoctrine()->getRepository(Member::class)->getClubRecentInactiveMembers($clubTools->getClub());

        return $this->render('Club/Member/list.html.twig', array('members' => $members, 'old_members' => $old_members, 'club' => $clubTools->getClub()));
    }

    /**
     * @Route("{club<\d+>}/donnees_personnelles/{member<\d+>}", name="member_personal_data")
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberPersonalData(Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        return $this->render('Club/Member/personal_data.html.twig', array('memberTools' => $memberTools));
    }

    /**
     * @Route("{club<\d+>}/creer_login/{member<\d+>}", name="member_login_create")
     * @param UserTools $userTools
     * @param SessionInterface $session
     * @param Request $request
     * @param Member $member
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberLoginCreate(UserTools $userTools, SessionInterface $session, Request $request, Member $member, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $form = $this->createForm(UserType::class, new User(), array('form' => 'create', 'data_class' => User::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $userTools->newUser($form->getData(), $this->getUser(), $form['Password']->getData(), $member);

            $session->set('duplicate', $userTools->isDuplicate());

            if ($session->get('duplicate'))
            {
                return $this->render('Club/Member/login_create.html.twig', array('form' => $form->createView()));
            }

            return $this->redirectToRoute('club_members_list');
        }

        return $this->render('Club/Member/login_create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/detail_licence/{member<\d+>}", name="member_licence_detail")
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberLicenceDetail(Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        return $this->render('Club/Member/licence_detail.html.twig', array('memberTools' => $memberTools));
    }

    /**
     * @Route("{club<\d+>}/detail_grades/{member<\d+>}", name="member_grades_detail")
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberGradesDetail(Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        return $this->render('Club/Member/grade_detail.html.twig', array('memberTools' => $memberTools));
    }

    /**
     * @Route("{club<\d+>}/detail_stages/{member<\d+>}", name="member_stages_detail")
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberStagesDetail(Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        return $this->render('Club/Member/stages_detail.html.twig', array('memberTools' => $memberTools));
    }

    /**
     * @Route("{club<\d+>}/detail_titres/{member<\d+>}", name="member_titles_detail")
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function memberTitlesDetail(Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        return $this->render('Club/Member/titles_detail.html.twig', array('memberTools' => $memberTools));
    }

    /**
     * @Route("{club<\d+>}/membre/{member<\d+>}/candidature", name="member_application")
     * @param Request $request
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function memberApplication(Request $request, Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $memberTools->setMember($member);

        $form = $this->createForm(GradeType::class, $memberTools->getGrades()['exam']['grade'], array('form' => 'exam_application', 'data_class' => Grade::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $memberTools->application($form->getData());

            return $this->redirectToRoute('club_members_list');
        }

        return $this->render('Club/Member/exam_application.html.twig', array('form' => $form->createView(), 'exam' => $form->getData()->getGradeExam()));
    }

    /**
     * @Route("{club<\d+>}/membre/{member<\d+>}/ajouter_kyu", name="member_kyu_add")
     * @param Request $request
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function memberKyuAdd(Request $request, Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $memberTools->setMember($member);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $form = $this->createForm(GradeType::class, $memberTools->newKyu(), array('form' => 'kyu_add', 'data_class' => Grade::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $memberTools->addKyu($form->getData());

            return $this->redirectToRoute('club_member_grades_detail', array('member' => $member->getMemberId()));
        }

        return $this->render('Club/Member/kyu_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/membre/{member<\d+>}/modifier_kyu/{grade<\d+>}", name="member_kyu_modify")
     * @param Request $request
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Grade $grade
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function memberKyuModify(Request $request, Member $member, MemberTools $memberTools, ClubTools $clubTools, Grade $grade, Club $club)
    {
        $clubTools->setClub($club);

        $memberTools->setMember($member);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $form = $this->createForm(GradeType::class, $grade, array('form' => 'kyu_modify', 'data_class' => Grade::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $memberTools->modifyKyu($form->getData());

            return $this->redirectToRoute('club_member_grades_detail', array('member' => $member->getMemberId()));
        }

        return $this->render('Club/Member/kyu_modify.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/membre/{member<\d+>}/supprimer_kyu/{grade<\d+>}", name="member_kyu_delete")
     * @param Request $request
     * @param Member $member
     * @param MemberTools $memberTools
     * @param ClubTools $clubTools
     * @param Club $club
     * @return RedirectResponse|Response
     */
    public function memberKyuDelete(Request $request, Member $member, MemberTools $memberTools, ClubTools $clubTools, Club $club)
    {
        $clubTools->setClub($club);

        $memberTools->setMember($member);

        if ($member->getMemberActualClub() !== $clubTools->getClub())
        {
            return $this->redirectToRoute('club_members_list');
        }

        $form = $this->createForm(GradeType::class, $memberTools->newKyu(), array('form' => 'add_kyu', 'data_class' => Grade::class));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $memberTools->addKyu($form->getData());

            return $this->redirectToRoute('club_member_grades_detail', array('member' => $member->getMemberId()));
        }

        return $this->render('Club/Member/kyu_modify.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("{club<\d+>}/liste_gestionnaire", name="manager_index")
     * @param ClubTools $clubTools
     * @param Club $club
     * @return Response
     */
    public function managerIndex(ClubTools $clubTools, Club $club): Response
    {
        $clubTools->setClub($club);

        return $this->render('Club/Manager/index.html.twig', array('clubTools' => $clubTools));
    }

    /**
     * @Route("{club<\d+>}/rechercher_membres", name="search_members")
     * @param Request $request
     * @param Club $club
     * @return Response
     */
    public function searchMembers(Request $request, Club $club): Response
    {
        $search = null; $results = null;

        $form = $this->createForm(ClubType::class, $search, array('form' => 'search_members', 'data_class' => null));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $results = $this->getDoctrine()->getRepository(Member::class)->getFullSearchClubMembers($form->get('Search')->getData(), $club->getClubId());

            return $this->render('Club/Member/search.html.twig', array('form' => $form->createView(), 'results' => $results));
        }

        return $this->render('Club/Member/search.html.twig', array('form' => $form->createView(), 'results' => $results));
    }
}
