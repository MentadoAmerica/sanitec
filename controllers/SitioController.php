<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\Materiales;
use app\models\RegistroForm;
use app\models\Sanitarios;
use app\models\Servicios;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\Security;
use yii\mail\MailerInterface;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SitioController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MailerInterface $mailer,
        private readonly Security $security,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        }

        if ((int) Yii::$app->user->identity->id_rol === 1) {
            return $this->render('admin-dashboard', $this->getAdminDashboardData());
        }

        return $this->render('index');
    }

    private function getAdminDashboardData(): array
    {
        $sanitarios = [
            'total' => (int) Sanitarios::find()->count(),
            'activos' => (int) Sanitarios::find()->where(['estado' => 1])->count(),
            'inactivos' => (int) Sanitarios::find()->where(['estado' => 0])->count(),
        ];
        $totalServicios = (int) Servicios::find()->count();
        $db = Yii::$app->db;
        $totalVerificaciones = (int) $db->createCommand(
            'SELECT COUNT(*) FROM {{%verificaciones}}',
        )->queryScalar();
        $satisfaccion = $db->createCommand(
            'SELECT AVG(calificacion_general) FROM {{%evaluaciones_satisfaccion}}',
        )->queryScalar();
        $satisfaccion = $satisfaccion !== null ? (float) $satisfaccion : null;

        $materialesBajos = Materiales::find()
            ->select(['nombre', 'stock_actual', 'stock_minimo', 'unidad_medida'])
            ->where(['estado' => 1])
            ->andWhere('[[stock_actual]] <= [[stock_minimo]]')
            ->orderBy(['stock_actual' => SORT_ASC, 'nombre' => SORT_ASC])
            ->limit(5)
            ->asArray()
            ->all();
        $totalMaterialesBajos = (int) Materiales::find()
            ->where(['estado' => 1])
            ->andWhere('[[stock_actual]] <= [[stock_minimo]]')
            ->count();

        $reportCounts = ['abierto' => 0, 'en proceso' => 0, 'cerrado' => 0];
        foreach ($db->createCommand(
            'SELECT estado, COUNT(*) AS cantidad FROM {{%reportes}} GROUP BY estado',
        )->queryAll() as $reporte) {
            $estado = mb_strtolower(trim($reporte['estado']), 'UTF-8');
            $estado = strtr($estado, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u']);

            if (in_array($estado, ['cerrado', 'cerrada'], true)) {
                $reportCounts['cerrado'] += (int) $reporte['cantidad'];
            } elseif (in_array($estado, ['en proceso', 'en_proceso'], true)) {
                $reportCounts['en proceso'] += (int) $reporte['cantidad'];
            } else {
                $reportCounts['abierto'] += (int) $reporte['cantidad'];
            }
        }

        $pendientesAbastecimiento = (int) $db->createCommand(
            'SELECT COUNT(*)
             FROM {{%reportes}} r
             INNER JOIN {{%tipos_reporte}} tr ON tr.id_tipo_reporte = r.id_tipo_reporte
             WHERE LOWER(tr.categoria) = :categoria
               AND LOWER(r.estado) NOT IN (:cerrado, :cerrada)',
            [
                ':categoria' => 'abastecimiento',
                ':cerrado' => 'cerrado',
                ':cerrada' => 'cerrada',
            ],
        )->queryScalar();

        $reportesRecientes = $db->createCommand(
            'SELECT
                tr.nombre AS tipo,
                r.estado,
                COALESCE(NULLIF(r.prioridad, \'\'), tr.prioridad) AS prioridad,
                e.nombre AS edificio,
                s.nombre AS sanitario,
                r.fecha_hora_reporte
             FROM {{%reportes}} r
             INNER JOIN {{%tipos_reporte}} tr ON tr.id_tipo_reporte = r.id_tipo_reporte
             INNER JOIN {{%sanitarios}} s ON s.id_sanitario = r.id_sanitario
             INNER JOIN {{%edificios}} e ON e.id_edificio = s.id_edificio
             ORDER BY r.fecha_hora_reporte DESC
             LIMIT 5',
        )->queryAll();

        $serviciosRecientes = $db->createCommand(
            'SELECT
                COALESCE(
                    NULLIF(TRIM(CONCAT_WS(\' \', u.nombre, u.apellidos)), \'\'),
                    p.tipo_personal,
                    \'Personal\'
                ) AS trabajador,
                s.nombre AS sanitario,
                e.nombre AS edificio,
                sv.fecha,
                sv.hora_checkin,
                sv.hora_checkout
             FROM {{%servicios}} sv
             INNER JOIN {{%personal}} p ON p.id_personal = sv.id_personal
             LEFT JOIN {{%usuarios}} u ON u.id_usuario = p.id_usuario
             INNER JOIN {{%sanitarios}} s ON s.id_sanitario = sv.id_sanitario
             INNER JOIN {{%edificios}} e ON e.id_edificio = s.id_edificio
             ORDER BY sv.fecha DESC, sv.hora_checkin DESC
             LIMIT 5',
        )->queryAll();

        return [
            'sanitarios' => $sanitarios,
            'totalServicios' => $totalServicios,
            'totalVerificaciones' => $totalVerificaciones,
            'satisfaccion' => $satisfaccion,
            'totalMaterialesBajos' => $totalMaterialesBajos,
            'reportCounts' => $reportCounts,
            'pendientesAbastecimiento' => $pendientesAbastecimiento,
            'materialesBajos' => $materialesBajos,
            'reportesRecientes' => $reportesRecientes,
            'serviciosRecientes' => $serviciosRecientes,
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';
        $model = new LoginForm();

        if ($model->load($this->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->contrasena = '';

        return $this->render('login', ['model' => $model]);
    }

    public function actionRegistro(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';
        $model = new RegistroForm();

        if ($model->load($this->request->post()) && $model->registrar()) {
            Yii::$app->session->setFlash(
                'success',
                'Tu cuenta se creó correctamente. Ya puedes iniciar sesión.',
            );

            return $this->redirect(['login']);
        }

        return $this->render('registro', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact(): Response|string
    {
        $model = new ContactForm();

        $contact = $model->load($this->request->post()) && $model->contact(
            $this->mailer,
            Yii::$app->params['adminEmail'],
            Yii::$app->params['senderEmail'],
            Yii::$app->params['senderName'],
        );

        if ($contact) {
            Yii::$app->session->setFlash(
                'success',
                'Thank you for contacting us. We will respond to you as soon as possible.',
            );

            return $this->refresh();
        }

        return $this->render('contact', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }
}
