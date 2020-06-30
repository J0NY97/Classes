<?php
	class Matrix
	{
		const IDENTITY = "IDENTITY";
		const SCALE = "SCALE";
		const RX = "Ox ROTATION";
		const RY = "Oy ROTATION";
		const RZ = "Oz ROTATION";
		const TRANSLATION = "TRANSLATION";
		const PROJECTION = "PROJECTION";
		public $matrix;
		public $preset;
		private $_scale;
		public $angle;
		public $vtc;
		public $fov;
		public $ratio;
		public $near;
		public $far;
		static $verbose = False;

		function __construct(array $args = null)
		{
			if (isset($args))
			{
				if (isset($args["preset"]))
					$this->preset = $args["preset"];
				if (isset($args["scale"]))
						$this->_scale = $args["scale"];
				if (isset($args["angle"]))
						$this->angle = $args["angle"];
				if (isset($args["vtc"]))
						$this->vtc = $args["vtc"];
				if (isset($args["fov"]))
						$this->fov = $args["fov"];
				if (isset($args["ratio"]))
						$this->ratio = $args["ratio"];
				if (isset($args["near"]))
						$this->near = $args["near"];
				if (isset($args["far"]))
						$this->far = $args["far"];
				$this->check_errors();
				$this->matrix = array(array(0, 0 ,0 ,0),
									array(0, 0, 0, 0),
									array(0, 0, 0, 0),
									array(0, 0, 0, 0));
				$this->execute();
				if (self::$verbose)
				{
					if ($this->preset == self::IDENTITY)
						print ("Matrix $this->preset instance constructed\n");
					else
						print ("Matrix $this->preset preset instance constructed\n");
				}
			}
		}

		function __destruct()
		{
			print ("Matrix instance destructed\n");
		}

		function __toString()
		{
			$tmp = "M | vtcX | vtcY | vtcZ | vtxO\n";
			$tmp .= "-----------------------------\n";
			$tmp .= "x | %0.2f | %0.2f | %0.2f | %0.2f\n";
			$tmp .= "y | %0.2f | %0.2f | %0.2f | %0.2f\n";
			$tmp .= "z | %0.2f | %0.2f | %0.2f | %0.2f\n";
			$tmp .= "w | %0.2f | %0.2f | %0.2f | %0.2f";
			return (sprintf($tmp,
				$this->matrix[0][0], $this->matrix[0][1], $this->matrix[0][2], $this->matrix[0][3],
				$this->matrix[1][0], $this->matrix[1][1], $this->matrix[1][2], $this->matrix[1][3],
				$this->matrix[2][0], $this->matrix[2][1], $this->matrix[2][2], $this->matrix[2][3],
				$this->matrix[3][0], $this->matrix[3][1], $this->matrix[3][2], $this->matrix[3][3]
				));
		}

		static function doc()
		{
			$file_name = "Matrix.doc.txt";
			if (file_exists($file_name))
				return (file_get_contents($file_name));
			else
				return ("Doc file doesn't exist\n");
		}

		function check_errors()
		{
			if (($this->preset == self::SCALE && !isset($this->_scale)) ||
				(($this->preset == self::RX || $this->preset == self::RY
					|| $this->preste == self::RZ) && !isset($this->angle)) ||
					($this->preset == self::TRANSLATION && !isset($this->vtc)) ||
					($this->preset == self::PROJECTION &&
					!(isset($this->fov) && isset($this->ratio) &&
						isset($this->near) && isset($this->far))))
			{
				return ("error");
			}
		}

		function execute()
		{
			switch ($this->preset)
			{
				case (self::IDENTITY):
					$this->identity(1);
					break;
				case (self::TRANSLATION):
					$this->translation();
					break;
				case (self::SCALE):
					$this->identity($this->_scale);
					break;
				case (self::RX):
					$this->rotateX();
					break;
				case (self::RY):
					$this->rotateY();
					break;
				case (self::RZ):
					$this->rotateZ();
					break;
				case (self::PROJECTION):
					$this->projection();
					break;
			}
		}

		function identity($scale)
		{
			$this->matrix[0][0] = $scale;
			$this->matrix[1][1] = $scale;
			$this->matrix[2][2] = $scale;
			$this->matrix[3][3] = 1;
		}

		function translation()
		{
			$this->identity(1);
			$this->matrix[0][3] = $this->vtc->getX();
			$this->matrix[1][3] = $this->vtc->getY();
			$this->matrix[2][3] = $this->vtc->getZ();
		}

		function rotateX()
		{
			$this->identity(1);
			$this->matrix[0][0] = 1;
			$this->matrix[1][1] = cos($this->angle);
			$this->matrix[1][2] = -sin($this->angle);
			$this->matrix[2][1] = sin($this->angle);
			$this->matrix[2][2] = cos($this->angle);
		}

		function rotateY()
		{
			$this->identity(1);
			$this->matrix[0][0] = cos($this->angle);
			$this->matrix[0][2] = sin($this->angle);
			$this->matrix[1][1] = 1;
			$this->matrix[2][0] = -sin($this->angle);
			$this->matrix[2][2] = cos($this->angle);
		}

		function rotateZ()
		{
			$this->identity(1);
			$this->matrix[0][0] = cos($this->angle);
			$this->matrix[0][1] = -sin($this->angle);
			$this->matrix[1][0] = sin($this->angle);
			$this->matrix[1][1] = cos($this->angle);
			$this->matrix[2][2] = 1;
		}

		function projection()
		{
			$this->identity(1);
			$this->matrix[1][1] = 1 / tan(0.5 * deg2rad($this->fov));
			$this->matrix[0][0] = $this->matrix[1][1] / $this->ratio;
			$this->matrix[2][2] = -1 * (-$this->near - $this->far) / ($this->near - $this->far);
			$this->matrix[3][3] = -1;
			$this->matrix[2][3] = (2 * $this->near * $this->far) / ($this->near - $this->far);
			$this->matrix[3][3] = 0;
		}

		function mult(Matrix $rhs)
		{
			$new_matrix = array(array(),array(),array(),array());
			for ($x = 0; $x < 4; $x++)
			{
				for ($y = 0; $y < 4; $y++)
				{
					$new_matrix[$x][$y] = 0;
					for ($i = 0; $i < 4; $i++)
					{
						$new_matrix[$x][$y] += $this->matrix[$x][$i] * $rhs->matrix[$i][$y];
					}
				}
			}
			$new = new Matrix();
			$new->matrix = $new_matrix;
			return ($new);
		}

		function transformVertex(Vertex $vtx)
		{
			return (new Vertex(array(
				'x' => ($vtx->getX() * $this->matrix[0][0]) +
						($vtx->getY() * $this->matrix[0][1]) +
						($vtx->getZ() * $this->matrix[0][2]) +
						($vtx->getW() * $this->matrix[0][3]),
				'y' => ($vtx->getX() * $this->_matrix[1][0]) +
						($vtx->getY() * $this->matrix[1][1]) +
					   	($vtx->getZ() * $this->matrix[1][2]) +
						($vtx->getW() * $this->matrix[1][3]),
				'z' => ($vtx->getX() * $this->matrix[2][0]) +
						($vtx->getY() * $this->matrix[2][1]) +
						($vtx->getZ() * $this->matrix[2][2]) +
						($vtx->getW() * $this->matrix[2][3]),
				'w' => ($vtx->getX() * $this->matrix[3][0]) +
						($vtx->getY() * $this->matrix[3][1]) +
						($vtx->getZ() * $this->matrix[3][2]) +
						($vtx->getW() * $this->matrix[3][3]),
				'color' => $vtx->getColor())));
		}
	}
?>
